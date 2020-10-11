<template>
  <div class="cptm-placeholder-blcok" :class="containerClass">
    <p class="cptm-placeholder-label" :class="{ hide: selectedWidgets.length }">
      {{ label }}
    </p>

    <div class="cptm-widget-insert-area">
      <div class="cptm-widget-insert-wrap">
        <div class="cptm-widget-insert-modal-container">
          <widgets-window
            :availableWidgets="availableWidgets"
            :acceptedWidgets="acceptedWidgets"
            :active="showWidgetsPickerWindow"
            :bottomAchhor="true"
            @widget-selection="$emit('insert-widget', $event)"
            @close="$emit('close-widgets-picker-window')"
          />
        </div>

        <a href="#" class="cptm-widget-insert-link" @click.prevent="$emit( 'open-widgets-picker-window' )">
          <span class="fa fa-plus"></span>
        </a>
      </div>
    </div>

    <div class="cptm-widget-preview-area" v-if="selectedWidgets.length">
      <template v-for="(widget, widget_key) in selectedWidgets">
        <component
          :is="activeWidgets[widget].type + '-card-widget'"
          :key="widget_key"
          :label="activeWidgets[widget].label"
          :options="activeWidgets[widget].options"
          @drag="$emit('drag-widget', widget)"
          @edit="$emit('edit-widget', widget)"
          @trash="$emit('trash-widget', widget)"
        >
        </component>
      </template>
    </div>
  </div>
</template>

<script>
export default {
    name: 'card-widget-placeholder',
    props: {
        containerClass: {
            type: String,
            default: '',
        },
        label: {
            type: String,
            default: '',
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
        maxWidget: {
            type: Number,
            default: 0,
        },
        maxWidgetInfoText: {
            type: String,
            default: 'Up to __DATA__ item{s} can be added',
        },
    },
};
</script>