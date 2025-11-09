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

    <!-- Example 5: With Infinite Scroll (10 items per page) -->
    <div class="example-section">
      <h3>Example 5: Infinite Scroll - Small Page Size</h3>
      <select-api-field
        v-model="selectedPostInfinite"
        label="Posts with Infinite Scroll"
        api-path="/wp-json/wp/v2/posts"
        :per-page="10"
        :enable-infinite-scroll="true"
        description="Loads 10 posts at a time. Scroll down to load more."
        resync-label="Refresh Posts"
      />
      <p class="selected-value">Selected: {{ selectedPostInfinite || 'None' }}</p>
    </div>

    <!-- Example 6: Infinite Scroll Disabled -->
    <div class="example-section">
      <h3>Example 6: Infinite Scroll Disabled</h3>
      <select-api-field
        v-model="selectedPageNoInfinite"
        label="Pages without Infinite Scroll"
        api-path="/wp-json/wp/v2/pages"
        :enable-infinite-scroll="false"
        description="Loads all available items in a single request"
      />
      <p class="selected-value">Selected: {{ selectedPageNoInfinite || 'None' }}</p>
    </div>

    <!-- Example 7: Custom Pagination Parameters -->
    <div class="example-section">
      <h3>Example 7: Custom Pagination Params</h3>
      <select-api-field
        v-model="selectedCustomPagination"
        label="Custom API Pagination"
        api-path="/wp-json/wp/v2/posts"
        :per-page="15"
        :scroll-threshold="50"
        page-param="page"
        per-page-param="per_page"
        description="Custom scroll threshold (50px from bottom)"
      />
      <p class="selected-value">Selected: {{ selectedCustomPagination || 'None' }}</p>
    </div>

    <!-- Example 8: Media Library with Infinite Scroll -->
    <div class="example-section">
      <h3>Example 8: Media Library</h3>
      <select-api-field
        v-model="selectedMedia"
        label="Select Media"
        api-path="/wp-json/wp/v2/media"
        :per-page="20"
        :api-params="{ media_type: 'image' }"
        description="Browse media library with infinite scroll"
      />
      <p class="selected-value">Selected Media ID: {{ selectedMedia || 'None' }}</p>
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
      selectedCustomOption: '',
      selectedPostInfinite: '',
      selectedPageNoInfinite: '',
      selectedCustomPagination: '',
      selectedMedia: ''
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

