export const localWishlist = {
    get() {
        return JSON.parse(localStorage.getItem('wishlist') || '[]');
    },
    add(productId) {
        const wishlist = this.get();
        if (!wishlist.includes(productId)) {
            wishlist.push(productId);
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
        }
    },
    remove(productId) {
        let wishlist = this.get();
        wishlist = wishlist.filter(id => id !== productId);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
    },
    count() {
        return this.get().length;
    }
};
