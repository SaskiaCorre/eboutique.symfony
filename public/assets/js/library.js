export const formatPrice = (price) => {
    return Intl.NumberFormat('fr-FR', { 
        style: 'currency', 
        currency: 'EUR' 
    }).format(price);
};

export const addFlashMessage = (message, status = "success") => {
    let text = `
    <div class="alert alert-${status}" role="alert">
    ${message}
    </div>
    `
    let audio = document.createElement("audio")
    audio.src = "/assets/audios/success.wav"

    audio.play()
    document.querySelector(".notification").innerHTML += text

    setTimeout(() => {
        document.querySelector(".notification").innerHTML = ""
    }, 2000)
}

export const fetchData = async (requestUrl) => {
    let response = await fetch(requestUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // nécessaire pour Symfony
        }
    });

    return await response.json()
}

export const manageCartLink = async (event) => {
    event.preventDefault();
    console.log("manageCartLink");

    let link = event.target.href ? event.target : event.target.parentNode
    let requestUrl = link.href

    console.log({ requestUrl });

    const cart = await fetchData(requestUrl)
    console.log(cart);

    // let productId = requestUrl.split('/')[5] ==> moins robuste que:;
    let segments = requestUrl.split('/');
    let productId = segments[segments.length - 1];
    let product = await fetchData("/product/get/" + productId)

    if (requestUrl.search('/cart/add/') != -1) {
        // add to cart
        if (product) {
            addFlashMessage(`Produit (${product.name}) ajouté au panier !`)
        } else {
            addFlashMessage("Produit ajouté au panier !")
        }
    }
    if (requestUrl.search('/cart/remove/') != -1) {
        // remove from cart
        if (product) {
            addFlashMessage(`Produit (${product.name}) supprimé du panier !`, "danger")
        } else {
            addFlashMessage("Produit supprimé du panier !", "danger")
        }
    }
    displayCart(cart)
    updateHeaderCart()
}

export const manageCompareLink = async (event) => {
    event.preventDefault();
    console.log("manageCompareLink");
    let link = event.target.href ? event.target : event.target.parentNode
    let requestUrl = link.href

    console.log({ requestUrl });

    const compare = await fetchData(requestUrl)
    console.log(compare);

    // let productId = requestUrl.split('/')[5];
    let segments = requestUrl.split('/');
    let productId = segments[segments.length - 1];
    let product = await fetchData("/product/get/" + productId)

    if (requestUrl.search('/compare/add/') != -1) {
        // add to cart
        if (product) {
            addFlashMessage(`Produit (${product.name}) ajouté à Compare !`)
        } else {
            addFlashMessage("Produit ajouté à Compare !")
        }
    }
    if (requestUrl.search('/compare/remove/') != -1) {
        // remove from cart
        if (product) {
            addFlashMessage(`Produit (${product.name}) supprimé de Compare !`, "danger")
        } else {
            addFlashMessage("Produit supprimé Compare !", "danger")
        }
    }
    displayCompare()
}

export const manageWishListLink = async (event) => {
    event.preventDefault();
    console.log("manageWishListLink");
    let link = event.target.href ? event.target : event.target.parentNode
    let requestUrl = link.href

    console.log({ requestUrl });

    const wishlist = await fetchData(requestUrl)
    console.log(wishlist);

    // let productId = requestUrl.split('/')[5];
    let segments = requestUrl.split('/');
    let productId = segments[segments.length - 1];
    let product = await fetchData("/product/get/" + productId)

    if (requestUrl.search('/wishlist/add/') != -1) {
        // add to cart
        if (product) {
            addFlashMessage(`Produit (${product.name}) ajouté aux Favoris !`)
        } else {
            addFlashMessage("Produit ajouté aux Favoris !")
        }
    }
    if (requestUrl.search('/wishlist/remove/') != -1) {
        // remove from cart
        if (product) {
            addFlashMessage(`Produit (${product.name}) supprimé des Favoris !`, "danger")
        } else {
            addFlashMessage("Produit supprimé des Favoris !", "danger")
        }
    }
    displayWishlist(wishlist)
}

export const displayCompare = async (compare = null) => {
    // addCompareEventListener()
    let tbody = document.querySelector('table.compare_table tbody')
    if (tbody) {
        
        if (!compare) {
            compare = await fetchData("/compare/get")
        }
        
        if(compare){
            
            let imageContainer = document.querySelector('table.compare_table tbody tr.pr_image')
            imageContainer.innerHTML = ""
            let nameContainer = document.querySelector('table.compare_table tbody tr.pr_title')
            nameContainer.innerHTML = ""
            let priceContainer = document.querySelector('table.compare_table tbody tr.pr_price')
            priceContainer.innerHTML = ""
            let stockContainer = document.querySelector('table.compare_table tbody tr.pr_stock')
            stockContainer.innerHTML = ""
            let addToCart = document.querySelector('table.compare_table tbody tr.pr_add_to_cart')
            addToCart.innerHTML = ""
            let removeFromCart = document.querySelector('table.compare_table tbody tr.pr_remove')
            removeFromCart.innerHTML = ""
            console.log("compare value:", compare)
            compare.forEach((product) => {
                imageContainer.innerHTML += `
                <td class="product-thumbnail">
                    <img src="/assets/images/products/${product.imageUrls[0]}" alt="compare-img">
                </td>
                `
                nameContainer.innerHTML += `
                <td class="product-name">
                    <a href="shop-product-detail.html">${product.name}</a>
                </td>
                `
                priceContainer.innerHTML += `
                <td class="product-price">
                    <span class="price">${formatPrice(product.soldePrice)}</span>
                </td>
                `
                stockContainer.innerHTML += `
                <td class="pr_stock">
                    ${ product.stock }
                    <span class="badge badge-pill badge-success">En stock</span>
                </td>
                `
                addToCart.innerHTML += `
                <td class="product-add-to-cart">
                    <a href="/cart/add/${product.id}" 
                        class="btn btn-fill-out add-to-cart">
                        <i class="icon-basket-loaded"></i> 
                            Ajouter au panier
                    </a>
                </td>
                `
                removeFromCart.innerHTML += `
                <td class="product-remove">
                    <a href="/compare/remove/${product.id}" class="remove_compare_item">
                        <span>Supprimer</span>
                        <i class="ti-close"></i>
                    </a>
                </td>
                `            
            });
        }
    }
    addCompareEventListener()
}
        
export const addCompareEventListener = () => {
    let links = document.querySelectorAll(".add-to-compare, .compare_table .remove_compare_item")
    console.log("Liens Compare:",{ links });
    links.forEach(link => {
        link.addEventListener("click", manageCompareLink)
    });
}
export const addWishListEventListenerToLink = () => {
    let links = document.querySelectorAll(".add-to-wishlist, .wishlist_table .remove-to-wishlist")
    console.log("Liens Wishlist:",{ links });

    links.forEach(link => {
        link.addEventListener("click", manageWishListLink)
    });
}

export const addCartEventListenerToLink = () => {
    let links = document.querySelectorAll('.shop_cart_table tbody a')
    console.log("Liens Panier:",{ links });
    links.forEach((link) => {
        link.addEventListener("click", manageCartLink)
    })

    let add_to_cart_links = document.querySelectorAll('a.add-to-cart, a.item_remove, a.btn-addtocart')
    add_to_cart_links.forEach((link) => {
        link.addEventListener("click", manageCartLink)
    })
}

export const displayCart = (cart = null) => {
    updateHeaderCart(cart)
    addCartEventListenerToLink()
    if (!cart) {
        return
    }
    console.log(cart);
    let tbody = document.querySelector('.shop_cart_table tbody')
    let cart_sub_total_amount = document.querySelector('.cart_sub_total_amount')
    let cart_shipping_total_amount = document.querySelector('.cart_shipping_total_amount')
    let cart_total_amount = document.querySelector('.cart_total_amount')
    if (tbody) {
        tbody.innerHTML = ""
        console.log("cart value:", cart)
        cart.items.forEach((item) => {
            let { product, quantity, sub_total } = item
            let content = `
             <tr>
                <td class="product-thumbnail">
                    <a><img width="50" alt="product1"
                        src="/assets/images/products/${product.imageUrls[0]}">
                    </a>
                </td>
                <td data-title="Product" class="product-name">
                    <a>${product.name}</a>
                </td>
                <td data-title="Price" class="product-price">
                    ${formatPrice(product.soldePrice / 100)}
                </td>
                <td data-title="Quantity" class="product-quantity">
                    <div class="quantity">
                        <a href="/cart/remove/${product.id}/1">
                            <input type="button" value="-" class="minus" href="/cart/remove/${product.id}/1">
                        </a>
                        <input type="text" name="quantity" value="${quantity}" title="Qty" size="4" class="qty">
                        <a href="/cart/add/${product.id}/1">
                            <input type="button" value="+" class="plus">
                        </a>
                    </div>
                </td>
                    <td data-title="Total" class="product-subtotal">
                        ${formatPrice(sub_total / 100)} 
                    </td>
                    <td>
                        <a href="/cart/remove/${product.id}/${item.quantity}" class="product-remove">
                            <i class="ti-close"></i>
                        </a>
                    </td>
            </tr> 
            `
            tbody.innerHTML += content
        });

        cart_sub_total_amount.innerHTML = formatPrice(cart.sub_total / 100)
        cart_shipping_total_amount.innerHTML = formatPrice(cart.carrier.price / 100 )
        cart_total_amount.innerHTML = formatPrice( (cart.sub_total + cart.carrier.price) / 100 )
      
    }
    addCartEventListenerToLink()
}

export const displayWishlist = (wishlist = null) => {
   
    addWishListEventListenerToLink()
    if (!wishlist) {
        return
    }

    let tbody = document.querySelector('.wishlist_table tbody')

    if (tbody) {
        tbody.innerHTML = ""
        console.log("wishlist value:", wishlist)
        wishlist.forEach((product) => {
            let content = `
            <tr>
            <td class="product-thumbnail">
                <a href="#">
                    <img width="50" height="50" alt="product1"
                        src="/assets/images/products/${ product.imageUrls[0] }">
                </a>
            </td>
            <td data-title="Product" class="product-name"><a href="#">
                    ${ product.name }
                </a>
            </td>
            <td data-title="Price" class="product-price">
                ${ formatPrice(product.soldePrice) }
            </td>
            <td data-title="Stock Status" class="product-stock-status">
                ${ product.stock }
                <span class="badge badge-pill badge-success">
                    En stock
                </span>
            </td>
            <td class="product-add-to-cart">
                <a href="/cart/add/${product.id}/1" class="btn btn-fill-out btn-addtocart">
                    <i class="icon-basket-loaded"></i> 
                        Ajouter au panier
                </a>
            </td>
            <td >
                <a href="/wishlist/remove/${product.id}" class="remove-to-wishlist">
                    <i class="ti-close"></i>
                </a>
            </td>
        </tr>
             `
            tbody.innerHTML += content
        });

       
    }
    addWishListEventListenerToLink()

}
export const updateHeaderCart = async (cart = null) => {
    let cart_count = document.querySelector(".cart_count")
    let cart_list = document.querySelector(".cart_list")
    let cart_price_value = document.querySelector(".cart_price_value")
    if (!cart) {
        // cart not found
        cart = await fetchData("/cart/get")
    }


    // cart data found
    cart_count ? cart_count.innerHTML = cart?.cart_count : null
    cart_price_value ? cart_price_value.innerHTML = formatPrice(cart.sub_total / 100) : null

    if(cart_list){
        cart_list.innerHTML = ""
        cart.items.forEach(item => {
            let { product, quantity, sub_total } = item
            cart_list.innerHTML += `
            <li><a href="/cart/remove/${product.id}/${quantity}" class="item_remove">
                    <i class="ion-close"></i>
                </a>
                <a href="/product/${product.slug}">
                    <img width="50" height="50" alt="cart_thumb1" 
                    src="/assets/images/products/${product.imageUrls[0]}">
                    ${product.name}
                </a>
                <span class="cart_quantity"> 
                    ${quantity} x
                    <span class="cart_amount">
                        <span class="price_symbole">
                            ${formatPrice(product.soldePrice / 100)}
                        </span>
                    </span>
                </span>
            </li>
                `
        })

    }
    addCartEventListenerToLink()

}