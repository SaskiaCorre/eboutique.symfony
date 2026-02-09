<?php

namespace App\Controller\Api;

use App\Services\StripeService;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApiStripeController extends AbstractController
{
    #[Route('/api/stripe/payment-intent', name: 'app_stripe_payment-intent', methods: ['POST'])]
    public function index(StripeService $stripeService): Response
    {
        // Récupéré sur la doc Stripe > Paiement en ligne > Créer une page de paiement > Solutions de démarrage rapide > Créer une page de paiement à l'aide de composants intégrés: create.php
        $stripe = new StripeClient([
            // "api_key" => $stripeSecretKey,
            "stripe_version" => "2025-04-30.basil"
            ]);

        $YOUR_DOMAIN = 'http://localhost:4242';

        $checkout_session = $stripe->checkout->sessions->create([
                'ui_mode' => 'custom',
                'line_items' => [[
                    'price' => '{{PRICE_ID}}',
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'return_url' => $YOUR_DOMAIN . '/return.html?session_id={CHECKOUT_SESSION_ID}',
            ]);
        
        echo json_encode(array('clientSecret' => $checkout_session->client_secret));
        
        return $this->render('api/api_stripe/index.html.twig', [
            'controller_name' => 'ApiStripeController',
        ]);
    }
}
