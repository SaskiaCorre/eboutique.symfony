<?php

namespace App\Controller;

use App\Repository\AddressRepository;
use App\Services\CartService;
use App\Services\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutController extends AbstractController
{
    private $session;

    public function __construct(
        private CartService $cartService,
        private RequestStack $requestStack,
    ) {
        $this->cartService = $cartService;
        $this->session = $requestStack->getSession();
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function index(
        AddressRepository $addressRepository,
        // Pour qu'index accède aux infos des clés privée et publique:
        StripeService $stripeService,
        ): Response
    {
        
        $cart = $this->cartService->getCartDetails();

        if(!count($cart['items'])){
            return $this->redirectToRoute('app_home');
        }

        // Vérif que l'user est connecté
        $user = $this->getUser();

        if(!$user){
            // Avant de rediriger, il faut donner des infos sur la session (la page ciblée)
            $this->session->set("next", "app_checkout");
            return $this->redirectToRoute("app_login");
        }
        
        $addresses = $addressRepository->findByUser($user);
        // Récupérer le panier:
        $cart_json = json_encode($cart);

        $publicKey = $stripeService->getPublicKey();

        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
            'cart' => $cart,
            'cart_json' => $cart_json,
            'public_key' => $publicKey,
            // Tableau de adresses:
            'addresses' => $addresses,
            
        ]);
    }
}
