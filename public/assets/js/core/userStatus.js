export function setUserLoggedIn(status) {
    window.USER_LOGGED_IN = status;
}

export function setUserWishlistCount (count) {
    window.USER_WISHLIST_COUNT = count;
}

// el parametro ids representa o dedberian ir los id´s de los productos que estan en la wishlist
export function setUserWishlist(ids = []) {
    window.USER_WISHLIST = ids;
}