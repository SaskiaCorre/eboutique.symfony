<?php

namespace App\Controller;

use App\Repository\CarrierRepository;
use App\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private CarrierRepository $carrierRepo,
    ) {
        $this->cartService = $cartService;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartService->getCartDetails();

        $carriers = $this->carrierRepo->findAll();

        forEach($carriers as $key => $carrier) {
            $carriers[$key] =  [
                'id' => $carrier->getid(),
                'name' => $carrier->getName(),
                'description' => $carrier->getDescription(),
                'price' => $carrier->getPrice(),
            ];
        }

        $cart_json = json_encode($cart);
        $carriers_json = json_encode($carriers);

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
            'carriers' => $carriers,
            'cart_json' => $cart_json,
            'carriers_json' => $carriers_json,
        ]);
    }

    #[Route('/cart/add/{productId}/{count}', name: 'app_add_to_cart')]
    public function addToCart(string $productId, $count = 1): Response
    {
        $this->cartService->addToCart($productId, $count);
        $cart = $this->cartService->getCartDetails();

        return $this->json($cart);
        // Sinon, redirige vers la page panier
    }

    #[Route('/cart/remove/{productId}/{count}', name: 'app_remove_to_cart')]
    public function removeToCart(string $productId, $count = 1): Response
    {
        $this->cartService->removeToCart($productId, $count);
        $cart = $this ->cartService->getCartDetails();
        
        return $this->json($cart);
    }

    #[Route('/cart/get', name: 'app_get_cart')]
    public function getCart(): Response
    {
        $cart = $this ->cartService->getCartDetails();
        
        return $this->json($cart);
    }

    #[Route('/cart/carrier', name: 'app_update_cart_carrier', methods: ["POST"])]
    public function updateCartCarrier(Request $req): Response
    {
        // Récupérer name="carrierId" du <select> de <form> pour acceder à Payload
        $id = $req->getPayload()->get("carrierId");
        
        // Récupérer le carrier sélectionné
        $carrier = $this->carrierRepo->findOneById($id);

        // Si on ne retrouve pas le carrier, on redirge vers home
        if(!$carrier){
            return $this->redirectToRoute("app_home");
        }

        // MAJ du panier: s'il existe, on a B du CartService update avec
            // pour valeur le tableau des valeurs (infos) du carrier 
        $this ->cartService->update("carrier", [
            'id' => $carrier->getid(),
            'name' => $carrier->getName(),
            'description' => $carrier->getDescription(),
            'price' => $carrier->getPrice(),
        ]);
        // Aprés la MAJ, on redirige vers le panier
        return $this->redirectToRoute("app_cart");
    }
}
