<template>
  <div class="select-api-field-examples">
    <h2>Select API Field Examples</h2>

    <!-- Example 1: Basic WordPress Posts -->
    <div class="example-section">
      <h3>Example 1: WordPress Posts</h3>
      <select-api-field
        v-model="selectedPost"
        label="Select Post"
        api-path="/wp-json/wp/v2/posts"
        :api-params="{ per_page: 20, status: 'publish' }"
        description="Select a published post from WordPress"
        resync-label="Refresh Posts"
      />
      <p class="selected-value">Selected Post ID: {{ selectedPost || 'None' }}</p>
    </div>

    <!-- Example 2: Categories -->
    <div class="example-section">
      <h3>Example 2: Categories</h3>
      <select-api-field
        v-model="selectedCategory"
        label="Select Category"
        api-path="/wp-json/wp/v2/categories"
        :api-params="{ per_page: 50, orderby: 'name' }"
        description="Select a category"
        resync-label="Refresh Categories"
        @update="handleCategoryChange"
      />
      <p class="selected-value">Selected Category ID: {{ selectedCategory || 'None' }}</p>
    </div>

    <!-- Example 3: Without Resync Button -->
    <div class="example-section">
      <h3>Example 3: Without Resync Button</h3>
      <select-api-field
        v-model="selectedUser"
        label="Select User"
        api-path="/wp-json/wp/v2/users"
        :api-params="{ per_page: 100 }"
        :show-resync-button="false"
        description="User list without resync option"
      />
      <p class="selected-value">Selected User ID: {{ selectedUser || 'None' }}</p>
    </div>

    <!-- Example 4: With Event Handlers -->
    <div class="example-section">
      <h3>Example 4: With Event Handlers</h3>
      <select-api-field
        v-model="selectedCustomOption"
        label="Custom Options"
        api-path="/wp-json/wp/v2/tags"
        description="With event handlers"
        resync-label="Refresh"
        @update="handleUpdate"
        @resync="handleResync"
      />
      <p class="selected-value">Selected: {{ selectedCustomOption || 'None' }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'select-api-field-example',
  
  data() {
    return {
      selectedPost: '',
      selectedCategory: '',
      selectedUser: '',
      selectedCustomOption: ''
    };
  },

  methods: {
    handleCategoryChange(value) {
      console.log('Category changed to:', value);
    },

    handleUpdate(value) {
      console.log('Value updated to:', value);
    },

    handleResync() {
      console.log('Resync button clicked');
    }
  }
};
</script>

