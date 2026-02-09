<?php

namespace App\Services;

use App\Repository\PaymentMethodRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class StripeService {
    private $session;

    public function __construct(
        
        private RequestStack $requestStack,
        private PaymentMethodRepository $paymentMethodRepo,
    ) {
        $this->session = $requestStack->getSession();
    }

    // Indiquer à Stripe si on est en mode production ou développement
    public function getPublicKey(){
        // Récupérer les données de Stripe
        $config = $this->paymentMethodRepo->findOneByName("Stripe");
        if($_ENV['APP_ENV'] === 'dev'){
            // développement
            return $config->getTestPublicApiKey();
        }else{
            // production
            return $config->getProdPublicApiKey();
        }
    }

    public function getPrivateKey(){
        $config = $this->paymentMethodRepo->findOneByName("Stripe");
        if($_ENV['APP_ENV'] === 'dev'){
            // développement
            return $config->getTestPrivateApiKey();
        }else{
            // production
            return $config->getProdPrivateApiKey();
        }
    }
}