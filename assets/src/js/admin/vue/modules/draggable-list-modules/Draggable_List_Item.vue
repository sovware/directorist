<template>
    <div
      v-if="listType === 'div'"
      class="directorist-draggable-list-item"
      :class="itemClassName"
      :draggable="canDrag"
      :style="listItemStyle"
      @dragstart="handleDragStart"
      @dragend="dragEnd"
    >
      <div class="directorist-draggable-list-item-slot" :style="slotStyle">
        <slot></slot>
      </div>
    </div>
  
    <li
      v-else
      class="directorist-draggable-list-item"
      :draggable="canDrag"
      :class="itemClassName"
      :style="listItemStyle"
      @dragstart="handleDragStart"
      @dragend="dragEnd"
    >
      <div class="directorist-draggable-list-item-slot" :style="slotStyle">
        <slot></slot>
      </div>
    </li>
  </template>
  
  <script>
  export default {
    name: "draggable-list-item",
    props: {
      canDrag: {
        default: true, // move | clone
      },
      dragType: {
        default: "move", // move | clone
      },
      itemClassName: {
        default: "",
      },
      listType: {
        default: "div", // div | li
      },
      dragHandle: {
        default: null, // CSS selector for drag handle
      },
    },
  
    computed: {
      listItemStyle() {
        let style = {};
  
        if (this.dragging && "move" === this.dragType) {
          style.height = "0";
          style.padding = "0";
          style.overflow = "hidden";
        }
  
        if (this.dragging && "clone" === this.dragType) {
          style.border = "2px dashed gray";
        }
  
        return style;
      },
  
      slotStyle() {
        return {
          opacity: this.dragging ? 0 : 1,
        };
      },
    },
  
    data() {
      return {
        dragging: false,
        dragFromHandle: false,
      };
    },
  
    mounted() {
      if (this.dragHandle && this.canDrag) {
        this.setupDragHandle();
      }
    },
  
    methods: {
      setupDragHandle() {
        const self = this;
        const dragHandleElement = this.$el.querySelector(this.dragHandle);
  
        if (dragHandleElement) {
          // Mark that drag is from handle when mousedown on handle
          dragHandleElement.addEventListener("mousedown", function () {
            self.dragFromHandle = true;
          });
  
          // Reset flag when mouse is released anywhere
          document.addEventListener("mouseup", function () {
            self.dragFromHandle = false;
          });
        }
      },
  
      handleDragStart(event) {
        // If dragHandle is specified, only allow drag from handle
        if (this.dragHandle && !this.dragFromHandle) {
          event.preventDefault();
          return false;
        }
  
        // Proceed with normal drag start
        this.dragStart();
      },
  
      dragStart() {
        const self = this;
        setTimeout(function () {
          self.dragging = true;
          self.$emit("drag-start");
        }, 0);
      },
  
      dragEnd() {
        this.dragging = false;
        this.dragFromHandle = false;
        this.$emit("drag-end");
      },
    },
  };
  </script>
  