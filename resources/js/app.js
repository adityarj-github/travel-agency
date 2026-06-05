import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import initAnimations from './animations';

Alpine.plugin(collapse);

// Kick off GSAP scroll animations once the DOM is ready.
if (document.readyState !== 'loading') {
    initAnimations();
} else {
    document.addEventListener('DOMContentLoaded', initAnimations);
}

// Wishlist heart toggle — posts to the JSON endpoint and flips state in place.
Alpine.data('wishlistToggle', (packageId, initial) => ({
    active: initial,
    loading: false,
    async toggle() {
        if (this.loading) return;
        this.loading = true;
        try {
            const res = await fetch(`/wishlist/${packageId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                },
            });
            if (res.ok) {
                const data = await res.json();
                this.active = data.added;
            } else if (res.status === 401 || res.status === 419) {
                window.location.href = '/login';
            }
        } catch (e) {
            // Network error — leave state unchanged.
        } finally {
            this.loading = false;
        }
    },
}));

// Booking form — live price estimate + coupon validation.
Alpine.data('bookingForm', (config) => ({
    symbol: config.symbol,
    unit: config.unit || 0,
    adults: config.adults || 1,
    children: config.children || 0,
    code: '',
    discount: 0,
    applied: false,
    loading: false,
    message: '',
    get travelers() {
        return (parseInt(this.adults) || 0) + (parseInt(this.children) || 0);
    },
    get subtotal() {
        return Math.round(this.unit * this.travelers * 100) / 100;
    },
    get total() {
        return Math.max(0, Math.round((this.subtotal - this.discount) * 100) / 100);
    },
    format(value) {
        return this.symbol + Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    resetCoupon() {
        this.discount = 0;
        this.applied = false;
        this.message = '';
    },
    async apply() {
        if (!this.code || this.subtotal <= 0) return;
        this.loading = true;
        this.message = '';
        try {
            const res = await fetch(config.applyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ code: this.code, subtotal: this.subtotal }),
            });
            const data = await res.json();
            if (res.ok && data.valid) {
                this.discount = data.discount;
                this.applied = true;
                this.message = data.message;
            } else {
                this.discount = 0;
                this.applied = false;
                this.message = data.message || 'Invalid coupon.';
            }
        } catch (e) {
            this.message = 'Could not validate coupon. Please try again.';
        } finally {
            this.loading = false;
        }
    },
}));

window.Alpine = Alpine;
Alpine.start();
