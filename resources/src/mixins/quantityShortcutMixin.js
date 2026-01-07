/**
 * Quantity Shortcut Mixin
 * 
 * Provides global keyboard shortcuts for quantity management:
 * - +/- keys: Adjust quantity of last added product
 * - * key: Enter multiply mode to set exact quantity
 * - Delete/Backspace: Remove last added item from details
 * 
 * Usage: 
 * 1. Import and add to mixins array
 * 2. Requires: this.details array, this.CalculTotal() method
 * 3. Optional: this.delete_Product_Detail(id) for delete functionality
 */
export default {
    data() {
        return {
            // Multiply mode state
            multiplyModeActive: false,
            multiplyBuffer: '',
            multiplyTimeout: null,
        };
    },

    methods: {
        // Main keyboard handler for quantity shortcuts
        handleQuantityShortcut(e) {
            // Detect key types
            const isPlus = e.key === '+' || (e.key === '=' && e.shiftKey) || e.key === 'Add';
            const isMinus = e.key === '-' || e.key === 'Subtract';
            const isMultiply = e.key === '*' || e.key === 'Multiply';
            const isDigit = /^[0-9]$/.test(e.key);
            const isDecimal = e.key === '.' || e.key === ',';
            const isEnter = e.key === 'Enter';
            const isEscape = e.key === 'Escape';
            const isBackspace = e.key === 'Backspace';
            const isDelete = e.key === 'Delete';

            // Handle multiply mode first (takes priority)
            if (this.multiplyModeActive) {
                e.preventDefault();
                e.stopPropagation();

                if (isDigit) {
                    this.multiplyBuffer += e.key;
                    this.resetMultiplyTimeout();
                    this.showMultiplyFeedback();
                    return;
                }

                if (isDecimal && !this.multiplyBuffer.includes('.')) {
                    this.multiplyBuffer += '.';
                    this.resetMultiplyTimeout();
                    this.showMultiplyFeedback();
                    return;
                }

                if (isBackspace) {
                    this.multiplyBuffer = this.multiplyBuffer.slice(0, -1);
                    if (this.multiplyBuffer === '') {
                        this.cancelMultiplyMode();
                    } else {
                        this.resetMultiplyTimeout();
                        this.showMultiplyFeedback();
                    }
                    return;
                }

                if (isEnter || isPlus || isMinus || isMultiply) {
                    this.applyMultiplyQuantity();
                    return;
                }

                if (isEscape) {
                    this.cancelMultiplyMode();
                    return;
                }

                // Any other key cancels multiply mode
                this.cancelMultiplyMode();
                return;
            }

            // If a modal is open, don't intercept
            const activeModal = document.querySelector('.modal.show');
            if (activeModal) return;

            // Check if user is in a regular input (not product search)
            const activeElement = document.activeElement;
            const isProductSearch = activeElement && activeElement.classList.contains('autocomplete-input');
            const isInput = activeElement && (
                activeElement.tagName === 'INPUT' ||
                activeElement.tagName === 'TEXTAREA' ||
                activeElement.isContentEditable
            );

            // Handle Delete/Backspace - remove last item (only when not in input)
            if ((isDelete || isBackspace) && !isInput && this.details && this.details.length > 0) {
                e.preventDefault();
                e.stopPropagation();
                const lastItem = this.details[0];

                // Use delete method if available, otherwise splice directly
                if (typeof this.delete_Product_Detail === 'function') {
                    this.delete_Product_Detail(lastItem.detail_id);
                } else {
                    this.details.splice(0, 1);
                    if (this.CalculTotal) this.CalculTotal();
                }

                if (this.makeToast) {
                    this.makeToast('info', `${lastItem.name} ${this.$t('Removed') || 'removed'}`, this.$t('Info') || 'Info');
                }
                return;
            }

            // Only intercept +/- if focused on product search or not in any input
            if (isInput && !isProductSearch) return;

            // Handle * to enter multiply mode
            if (isMultiply && this.details && this.details.length > 0) {
                e.preventDefault();
                e.stopPropagation();
                this.multiplyModeActive = true;
                this.multiplyBuffer = '';
                this.resetMultiplyTimeout();
                this.showMultiplyFeedback();
                return;
            }

            // Handle + and - for quantity adjustment
            if (!isPlus && !isMinus) return;

            if (this.details && this.details.length > 0) {
                e.preventDefault();
                e.stopPropagation();

                const lastItem = this.details[0];

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

                if (this.CalculTotal) this.CalculTotal();
                this.$forceUpdate();
            }
        },

        // Reset the auto-apply timeout for multiply mode
        resetMultiplyTimeout() {
            if (this.multiplyTimeout) {
                clearTimeout(this.multiplyTimeout);
            }
            // Auto-apply after 1.5 seconds of inactivity
            this.multiplyTimeout = setTimeout(() => {
                if (this.multiplyModeActive && this.multiplyBuffer) {
                    this.applyMultiplyQuantity();
                } else {
                    this.cancelMultiplyMode();
                }
            }, 1500);
        },

        // Apply the quantity from multiply buffer
        applyMultiplyQuantity() {
            const quantity = parseFloat(this.multiplyBuffer);
            if (isNaN(quantity) || quantity <= 0) {
                if (this.makeToast) {
                    this.makeToast('warning', this.$t('InvalidQuantity') || 'Invalid quantity', this.$t('Warning'));
                }
                this.cancelMultiplyMode();
                return;
            }

            if (this.details && this.details.length > 0) {
                const firstItem = this.details[0];

                // Check stock for items with stock limit
                if (firstItem.stock !== undefined && quantity > firstItem.stock) {
                    if (this.makeToast) {
                        this.makeToast('warning', `${this.$t('LowStock')} (${this.$t('Available') || 'Available'}: ${firstItem.stock})`, this.$t('Warning'));
                    }
                    this.cancelMultiplyMode();
                    return;
                }

                firstItem.quantity = quantity;
                if (this.CalculTotal) this.CalculTotal();
                this.$forceUpdate();

                if (this.makeToast) {
                    this.makeToast('success', `${this.$t('Quantity')} → ${quantity}`, this.$t('Updated') || 'Updated');
                }
            }

            this.cancelMultiplyMode();
        },

        // Cancel multiply mode and clear state
        cancelMultiplyMode() {
            this.multiplyModeActive = false;
            this.multiplyBuffer = '';
            if (this.multiplyTimeout) {
                clearTimeout(this.multiplyTimeout);
                this.multiplyTimeout = null;
            }
            this.hideMultiplyFeedback();
        },

        // Show visual feedback for multiply mode
        showMultiplyFeedback() {
            const displayValue = this.multiplyBuffer || '_';
            const productName = this.details && this.details.length > 0 ? this.details[0].name : '';

            let indicator = document.getElementById('multiply-mode-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'multiply-mode-indicator';
                indicator.style.cssText = `
          position: fixed;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          background: rgba(102, 126, 234, 0.95);
          color: white;
          padding: 20px 40px;
          border-radius: 12px;
          font-size: 24px;
          font-weight: bold;
          z-index: 10000;
          box-shadow: 0 10px 40px rgba(0,0,0,0.3);
          text-align: center;
          min-width: 200px;
        `;
                document.body.appendChild(indicator);
            }

            const setQtyText = this.$t ? (this.$t('SetQuantity') || 'Set Quantity') : 'Set Quantity';
            indicator.innerHTML = `
        <div style="font-size: 14px; opacity: 0.8; margin-bottom: 8px;">✖ ${setQtyText}</div>
        <div style="font-size: 36px;">${displayValue}</div>
        <div style="font-size: 12px; opacity: 0.7; margin-top: 8px;">${productName}</div>
        <div style="font-size: 11px; opacity: 0.5; margin-top: 8px;">Enter to apply • Esc to cancel</div>
      `;
            indicator.style.display = 'block';
        },

        // Hide visual feedback for multiply mode
        hideMultiplyFeedback() {
            const indicator = document.getElementById('multiply-mode-indicator');
            if (indicator) {
                indicator.style.display = 'none';
            }
        },

        // Initialize quantity shortcut listener
        initQuantityShortcuts() {
            window.addEventListener('keydown', this.handleQuantityShortcut);
        },

        // Cleanup quantity shortcut listener
        cleanupQuantityShortcuts() {
            window.removeEventListener('keydown', this.handleQuantityShortcut);
            this.cancelMultiplyMode();
        }
    },

    created() {
        this.$nextTick(() => {
            this.initQuantityShortcuts();
        });
    },

    beforeDestroy() {
        this.cleanupQuantityShortcuts();
    }
};
