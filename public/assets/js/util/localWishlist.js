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
        wishlist = wishlist.filter(id => id !== productId); // crea un nuevo array sin el productId especificado
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
    },
    has(productId) {
        return this.get().includes(productId);
    },
    count() {
        return this.get().length;
    }
};
