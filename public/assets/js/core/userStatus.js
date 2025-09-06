export function setUserLoggedIn(status) {
    window.USER_LOGGED_IN = status;
}

export function setUserWishlistCount (count) {
    window.USER_WISHLIST_COUNT = count;
}

export function setUserWishlist(ids = []) {
    window.USER_WISHLIST = ids;
}