/**
 * Quantity Shortcut Mixin
 * 
 * Provides global +/- keyboard shortcuts for adjusting quantity
 * of the last added product in the details list.
 * 
 * Usage: 
 * 1. Import and add to mixins array
 * 2. Requires: this.details array and this.CalculTotal() method
 */
export default {
    methods: {
        // Global +/- key handler for adjusting last added item quantity
        handleQuantityShortcut(e) {
            // Only handle + and - keys (numpad and regular)
            const isPlus = e.key === '+' || (e.key === '=' && e.shiftKey) || e.key === 'Add';
            const isMinus = e.key === '-' || e.key === 'Subtract';
            if (!isPlus && !isMinus) return;

            // If a modal is open, don't intercept
            const activeModal = document.querySelector('.modal.show');
            if (activeModal) return;

            // If focused on an input (other than product search), don't intercept
            const activeElement = document.activeElement;
            const isProductSearch = activeElement && activeElement.classList.contains('autocomplete-input');
            const isInput = activeElement && (
                activeElement.tagName === 'INPUT' ||
                activeElement.tagName === 'TEXTAREA' ||
                activeElement.isContentEditable
            );

            // Only intercept if focused on product search or not in any input
            if (isInput && !isProductSearch) return;

            // If details array has items, adjust last added item (first in array)
            if (this.details && this.details.length > 0) {
                e.preventDefault();
                e.stopPropagation();

                const lastItem = this.details[0]; // Most recently added is at index 0

                if (isPlus) {
                    // Check stock limit (if applicable)
                    if (lastItem.stock !== undefined && lastItem.quantity + 1 > lastItem.stock) {
                        if (this.makeToast) {
                            this.makeToast('warning', this.$t('LowStock'), this.$t('Warning'));
                        }
                        return;
                    }
                    lastItem.quantity++;
                } else {
                    // Don't go below 1
                    if (lastItem.quantity <= 1) {
                        if (this.makeToast) {
                            this.makeToast('warning', this.$t('MinimumQuantity') || 'Minimum quantity is 1', this.$t('Warning'));
                        }
                        return;
                    }
                    lastItem.quantity--;
                }

                // Recalculate totals
                if (this.CalculTotal) {
                    this.CalculTotal();
                }
                this.$forceUpdate();
            }
        },

        // Initialize quantity shortcut listener
        initQuantityShortcuts() {
            window.addEventListener('keydown', this.handleQuantityShortcut);
        },

        // Cleanup quantity shortcut listener
        cleanupQuantityShortcuts() {
            window.removeEventListener('keydown', this.handleQuantityShortcut);
        }
    },

    created() {
        // Auto-initialize if the mixin is used
        this.$nextTick(() => {
            this.initQuantityShortcuts();
        });
    },

    beforeDestroy() {
        this.cleanupQuantityShortcuts();
    }
};
