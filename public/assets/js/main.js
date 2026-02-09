import { displayCompare, displayCart, displayWishlist, formatPrice } from './library.js';

window.onload = () =>{
    let mainContent

    console.log("compare");
    mainContent = document.querySelector('.compare_container')
    let compare = JSON.parse(mainContent?.dataset?.compare || false)
    
    displayCompare(compare)

    /******************************* */
    
    console.log("wishlist");
    mainContent = document.querySelector('.wishlist_content')
    let wishlist = JSON.parse(mainContent?.dataset?.wishlist || false)
    
    displayWishlist(wishlist)

    /******************************* */    
    
    console.log("cart");
    // On a besoin du contenu du panier (récup de la class de son <div>)
    mainContent = document.querySelector(".cart_content")
    let cart = JSON.parse(mainContent?.dataset?.cart || false)

    // Récupérer le form en renseignant sa class + form (indisp pour récup le form)
    const form = document.querySelector(".carrier_form form")
    // On a aussi besoin du select
    const select = document.querySelector(".carrier_form select")

    
    // vérif qu'on récupère bien cart (il faut ajouter à la const .dataset)
    // console.log("Carrier js:", cart);

    // cart = mainContent ? JSON.parse(mainContent.dataset.cart) : false
    let carriers = JSON.parse(mainContent?.dataset?.carriers || false)
    // console.log("carriers L.133", carriers)
    // console.log("cart L.134", cart)

    // si cart est défini, je boucle sur tous ses éléménts
    if(cart){
        select.innerHTML = ""
        // On formate le prix pour chaque carrier
        carriers.forEach(carrier => {
            if(carrier.id == cart.carrier.id){
                select.innerHTML += `
                <option value="${carrier.id}" selected>
                    ${carrier.name} - Frais: ${formatPrice(carrier.price / 100)}
                </option>
                `
            }else{
                select.innerHTML += `
                <option value="${carrier.id}">
                    ${ carrier.name } - Frais: ${formatPrice(carrier.price / 100)}
                </option>
                `
            }
        });
    }
    // fonction handleSumit, qui prend event
    const handleSubmit = (event) => {
    // Stopper le comportement par défaut  
        event.preventDefaut();
    }

    const handleChange = async (event) => {
        event.preventDefault();
        // On récupère la valeur du <select> du <form> cad {{cart.carrier.id}}
        const id = event.target.value
        if( id) {
            const response = await fetch('/api/cart/update/carrier/' + id)
            const result = await response.json()
            // console.log('L75', result)
            if(result.isSuccess){
                const {data} = result
                displayCart(data)
            }
        }
        // Si c'est soumis, vérifier si on récupère bien le contenu avec id
        // console.log("L.803",({id}));

    }
    // Si c'est soumis
    form?.addEventListener('submit', handleSubmit)
    select?.addEventListener('change', handleChange)
    
    displayCart(cart)

}
