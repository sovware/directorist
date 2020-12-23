<template>
  <div
    class="cptm-placeholder-blcok"
    :class="getContainerClass"
    @drop.prevent="placeholderOnDrop()"
    @dragover.prevent="$emit('placeholder-dragover-on')"
    @dragenter="placeholderOnDragEnter()"
    @dragleave="placeholderOnDragLeave()"
  >
    <p class="cptm-placeholder-label" :class="{ hide: selectedWidgets.length }">
      {{ label }}
    </p>

    <div class="cptm-widget-insert-area">
      <div class="cptm-widget-insert-wrap">
        <div class="cptm-widget-insert-modal-container">
          <widgets-window
            :id="id"
            :availableWidgets="availableWidgets"
            :acceptedWidgets="acceptedWidgets"
            :activeWidgets="activeWidgets"
            :selectedWidgets="selectedWidgets"
            :active="showWidgetsPickerWindow"
            :maxWidget="maxWidget"
            :maxWidgetInfoText="maxWidgetInfoText"
            :bottomAchhor="true"
            @widget-selection="$emit('insert-widget', $event)"
            @close="$emit('close-widgets-picker-window')"
          />
        </div>

        <a
          href="#"
          class="cptm-widget-insert-link"
          @click.prevent="$emit('open-widgets-picker-window')"
        >
          <span class="fa fa-plus"></span>
        </a>
      </div>
    </div>

    <div class="cptm-widget-preview-area" v-if="selectedWidgets.length">
      <template v-for="(widget, widget_index) in selectedWidgets">
        <template v-if="hasValidWidget(widget)">
          <component
            :is="activeWidgets[widget].type + '-card-widget'"
            :key="widget_index"
            :label="( typeof availableWidgets[widget] !== 'undefined' ) ? availableWidgets[widget].label : 'Not Available'"
            :icon="( typeof activeWidgets[widget].icon === 'string' ) ? activeWidgets[widget].icon : ''"
            :options="activeWidgets[widget].options"
            :widgetDropable="widgetDropable"
            :canMove="typeof activeWidgets[widget].can_move !== undefined ? activeWidgets[widget].can_move : true"
            :canEdit="widgetHasOptions( activeWidgets[widget] )"
            @drag="$emit('drag-widget', widget)"
            @drop="$emit('drop-widget', widget)"
            @dragend="$emit('dragend-widget', widget)"
            @edit="$emit('edit-widget', widget)"
            @trash="$emit('trash-widget', widget)"
          >
          </component>
        </template>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: "card-widget-placeholder",
  props: {
    id: {
      type: String,
      default: "",
    },
    containerClass: {
      // type: String,
      default: "",
    },
    label: {
      type: String,
      default: "",
    },
    availableWidgets: {
      type: Object,
    },
    activeWidgets: {
      type: Object,
    },
    acceptedWidgets: {
      type: Array,
    },
    selectedWidgets: {
      type: Array,
    },
    showWidgetsPickerWindow: {
      type: Boolean,
      default: false,
    },
    widgetDropable: {
      type: Boolean,
      default: false,
    },
    maxWidget: {
      type: Number,
      default: 0,
    },
    maxWidgetInfoText: {
      type: String,
      default: "Up to __DATA__ item{s} can be added",
    },
  },
  created() {
    
  },
  computed: {
    getContainerClass() {
      if ( typeof this.containerClass === 'string' ) {
        return {
          [ this.containerClass ]: true,
          'drag-enter': this.placeholderDragEnter,
        }
      }
      if ( this.containerClass &&
          typeof this.containerClass === 'object' &&
        ! Array.isArray( this.containerClass )
      ) {
        return {
          ...this.containerClass,
          'drag-enter': this.placeholderDragEnter,
        }
      }
      
    }
  },
  data() {
    return {
      placeholderDragEnter: false,
    }
  },
  methods: {
    widgetHasOptions( active_widget ) {
      if ( ! active_widget.options && typeof active_widget.options !== 'object' ) { 
        return false;
      }
      if ( ! active_widget.options.fields && typeof active_widget.options.fields !== 'object' ) {
        return false;
      }
      return true;
    },
    placeholderOnDrop() {
      this.placeholderDragEnter = false;
      this.$emit('placeholder-on-drop')
    },
    placeholderOnDragEnter() {
      this.placeholderDragEnter = true;
      this.$emit('placeholder-on-dragenter');
    },
    placeholderOnDragLeave() {
      this.placeholderDragEnter = false;
      this.$emit('placeholder-on-dragleave');
    },
    hasValidWidget(widget_key) {
      if ( !this.activeWidgets[widget_key] && typeof this.activeWidgets[widget_key] !== "object" ) {
        return false;
      }
      if (typeof this.activeWidgets[widget_key].type !== "string") {
        return false;
      }
      return true;
    },
  },
};
</script>