<?php

namespace App\Services;

use App\Repository\ProductRepository;
use App\Repository\CarrierRepository;
use Symfony\Component\HttpFoundation\RequestStack;

// Initialisation du panier
class CartService {
    private $session;

    public function __construct(
        
        private RequestStack $requestStack,
        private ProductRepository $productRepo,
        private CarrierRepository $carrierRepo,
    ) {
        $this->session = $requestStack->getSession();
        // $this->productRepo = $productRepo;
    }

    public function get($key) 
    {
        // Si le panier est vide, on retourne un tab. vide
        return $this->session->get($key, []);
    }

    public function update($key, $cart)
    {
        return $this->session->set($key, $cart);
    }

    public function addToCart($productId, $count = 1) 
    {
        // Le panier doit être un tableau car plusieurs produits
        $cart = $this->get('cart');

        if(!empty($cart[$productId])){
            // product existe déjà dans le panier
            $cart[$productId] += $count;
        }else{
            // product n'est pas dans le panier, on le crée
            $cart[$productId] = $count;
        }

        $this->update("cart", $cart);
    }

    // Suppression de 1 ou plusieurs produits
    public function removeToCart($productId, $count = 1) 
    {
        $cart = $this->get('cart');

        if(isset($cart[$productId])){
            if($cart[$productId] <= $count){
                unset($cart[$productId]);
            }else{
                $cart[$productId] -= $count;
            }
            $this->update("cart", $cart);
        }
        
    }

    public function clearCart() {
        // Vider le panier => tableau vide
        $this->update("cart", []);
    }

    public function updateCarrier($carrier) {
        $this->update("carrier", $carrier);
    }

    public function getCartDetails()
    {
        // récupérer les données du panier
        $cart = $this->get('cart');
        $result = [
            'items' => [],
            'sub_total' => 0,
            'cart_count' => 0,
        ];
        
        $sub_total = 0;
        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepo->find($productId);
            if($product){
                $current_sub_total = $product->getSoldePrice()*100*$quantity;
                $sub_total += $current_sub_total;
                $result['items'] [] = [
                    'product' => [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'slug' => $product->getSlug(),
                        'imageUrls' => $product->getImageUrls(),
                        'soldePrice' =>$product->getSoldePrice()*100,
                        'regularPrice' =>$product->getRegularPrice()*100,
                    ],
                    'quantity' => $quantity,
                    'sub_total' => $current_sub_total,

            ];
            
            $result['sub_total'] = $sub_total;
            $result['cart_count'] += $quantity;
            
            }else{
                unset($cart[$productId]);
                $this->update("cart", $cart);
            }
        }
        // En dehors du forEach
        // Récupérer les info de carrier
        $carrier = $this->get("carrier");
        // Si ça n'existe pas, 
        if(!$carrier){
            // On récupère le cart de la bdd
            // $carrier indique le carrier par défaut (ici 0, le moins cher)
            $carrier = $this->carrierRepo->findAll()[0];
            // On recupère les infos qui nous î
            $carrier =  [
                'id' => $carrier->getid(),
                'name' => $carrier->getName(),
                'description' => $carrier->getDescription(),
                'price' => $carrier->getPrice(),
            ];
            // Stocker les infos dans la session, pour pvr y accéder plus tard
            $carrier = $this->update("carrier", $carrier);
        }
        
        $result["carrier"] = $carrier;
        $result['sub_total_with_carrier'] = $result['sub_total'] + $carrier['price'];

        return $result;
    }
}