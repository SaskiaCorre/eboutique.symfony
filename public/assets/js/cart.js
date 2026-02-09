import { displayCompare, addCompareEventListener, displayCart, updateHeaderCart } from "./library.js";

window.onload = () =>{ 
    
    console.log("cart");

    let mainContent = document.querySelector('.main_content')

    let cart = JSON.parse(mainContent?.dataset?.cart || false)
    
    displayCart(cart)

    updateHeaderCart(cart)
    
    console.log("compare");

    mainContent = document.querySelector('.compare_container')

    let compare = JSON.parse(mainContent?.dataset?.compare || false)

    addCompareEventListener()
    
    displayCompare(compare)

    console.log("wishlist");

    mainContent = document.querySelector('.wishlist_content')

    let wishlist = JSON.parse(mainContent?.dataset?.wishlist || false)

    addCompareEventListener()
    
    displayCompare(wishlist)
}
