<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

// Initialisation du panier
class CompareService {
    private $session;

    public function __construct(
        
        private RequestStack $requestStack,
        private ProductRepository $productRepo,
    ) {
        $this->session = $requestStack->getSession();
        $this->productRepo = $productRepo;
    }

    public function getCompare() 
    {
        // Si compare est vide, on retourne un tab. vide
        return $this->session->get("compare", []);
    }

    public function updateCompare($compare)
    {
        return $this->session->set("compare", $compare);
    }

    public function addToCompare($productId) 
    {
        // Le panier doit être un tableau car plusieurs produits
        $compare = $this->getCompare();

        if(!isset($compare[$productId])){
            // product existe déjà dans compare
            $compare[$productId] = 1;
            $this->updateCompare($compare);
        }
    }

    // Sppression de 1 ou plusieurs produits
    public function removeToCompare($productId) 
    {
        $compare = $this->getCompare();

        if(isset($compare[$productId])){
            unset($compare[$productId]);
            $this->updateCompare($compare);
        }        
    }

    public function clearCompare() {
        $this->updateCompare([]);
    }

    public function getCompareDetails()
    {

    $compare = $this->getCompare();
    $result = [];

    foreach ($compare as $productId => $quantity) {
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
            unset($compare[$productId]);
            $this->updateCompare($compare);
        }
    }
    return $result;
    }
}