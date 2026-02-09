<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

// Initialisation du panier
class WishListService {
    private $session;

    public function __construct(
        
        private RequestStack $requestStack,
        private ProductRepository $productRepo,
    ) {
        $this->session = $requestStack->getSession();
        $this->productRepo = $productRepo;
    }

    public function getWishList() 
    {
        // Si le panier est vide, on retourne un tab. vide
        return $this->session->get("wishlist", []);
    }

    public function updateWishList($wishlist)
    {
        return $this->session->set("wishlist", $wishlist);
    }

    public function addToWishList($productId) 
    {
        // Le panier doit être un tableau car plusieurs produits
        $wishlist = $this->getWishList();

        if(!isset($wishlist[$productId])){
            // product existe déjà dans wishlist
            $wishlist[$productId] = 1;
            $this->updateWishList($wishlist);
        }
    }

    // Sppression de 1 ou plusieurs produits
    public function removeToWishList($productId) 
    {
        $wishlist = $this->getWishList();

        if(isset($wishlist[$productId])){
            unset($wishlist[$productId]);
            $this->updateWishList($wishlist);
        }        
    }

    public function clearWishList() {
        $this->updateWishList([]);
    }

    public function getWishListDetails()
    {

    $wishlist = $this->getWishList();
    $result = [];
//  $quantity
    foreach ($wishlist as $productId => $quantity) {
        $product = $this->productRepo->find($productId);
        if($product){
            $result[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'slug' => $product->getSlug(),
                'imageUrls' => $product->getImageUrls(),
                'soldePrice' => $product->getSoldePrice(),
                'regularPrice' => $product->getRegularPrice(),
                'stock' => $product->getStock(),              
            ];
        }else{
            unset($wishlist[$productId]);
            $this->updateWishList($wishlist);
        }
    }
    return $result;
    }
}