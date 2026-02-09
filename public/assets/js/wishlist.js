import { addWishListEventListenerToLink, displayWishlist} from './library';
window.onload = () => {

    let mainContent = document.querySelector('.wishlist_content')

    let wishlist = JSON.parse(mainContent?.dataset?.wishlist || false)

    addWishListEventListenerToLink()

    displayWishlist(wishlist)
}
