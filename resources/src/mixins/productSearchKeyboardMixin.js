/**
 * Product Search Keyboard Navigation Mixin
 * 
 * Provides keyboard navigation for product search autocomplete dropdowns.
 * Features:
 * - Arrow Up/Down to navigate through search results
 * - Enter to select highlighted item (or single result)
 * - Escape to clear/close dropdown
 * 
 * Usage:
 * 1. Import and add to mixins: mixins: [productSearchKeyboardMixin]
 * 2. Add keyboard event handlers to search input:
 *    @keydown.down.prevent="onSearchArrowDown"
 *    @keydown.up.prevent="onSearchArrowUp"
 *    @keydown.enter.prevent="onSearchEnter"
 *    @keydown.esc="onSearchEscape"
 * 3. Add :class and @mouseenter to dropdown items:
 *    :class="{ 'is-highlighted': index === searchHighlightIndex }"
 *    @mouseenter="searchHighlightIndex = index"
 * 4. Add CSS for .is-highlighted class
 * 
 * Requires: product_filter array and SearchProduct(item) method in component
 */

export default {
    data() {
        return {
            searchHighlightIndex: -1
        };
    },

    watch: {
        // Reset highlight when search results change
        product_filter: {
            handler() {
                this.searchHighlightIndex = -1;
            },
            deep: true
        }
    },

    methods: {
        /**
         * Move highlight down in search results
         */
        onSearchArrowDown() {
            if (!this.product_filter || this.product_filter.length === 0) return;

            if (this.searchHighlightIndex < this.product_filter.length - 1) {
                this.searchHighlightIndex++;
            } else {
                // Wrap to beginning
                this.searchHighlightIndex = 0;
            }
        },

        /**
         * Move highlight up in search results
         */
        onSearchArrowUp() {
            if (!this.product_filter || this.product_filter.length === 0) return;

            if (this.searchHighlightIndex > 0) {
                this.searchHighlightIndex--;
            } else {
                // Wrap to end
                this.searchHighlightIndex = this.product_filter.length - 1;
            }
        },

        /**
         * Select highlighted item or single result on Enter
         */
        onSearchEnter() {
            if (!this.product_filter || this.product_filter.length === 0) return;

            // If an item is highlighted, select it
            if (this.searchHighlightIndex >= 0 && this.searchHighlightIndex < this.product_filter.length) {
                this.SearchProduct(this.product_filter[this.searchHighlightIndex]);
                this.searchHighlightIndex = -1;
                return;
            }

            // If only one result, select it automatically
            if (this.product_filter.length === 1) {
                this.SearchProduct(this.product_filter[0]);
                this.searchHighlightIndex = -1;
            }
        },

        /**
         * Clear search results on Escape
         */
        onSearchEscape() {
            this.product_filter = [];
            this.searchHighlightIndex = -1;
            this.search_input = '';
        }
    }
};
