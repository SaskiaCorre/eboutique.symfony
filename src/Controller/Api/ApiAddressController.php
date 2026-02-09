<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ApiAddressController extends AbstractController
{
    #[Route('/address', name: 'app_post_address', methods: ['POST'])]
    public function index(
        Request $req,
        AddressRepository $addressRepository, 
        EntityManagerInterface $manager,
    ): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->json([
                "isSuccess" => false,
                "message" => "Accès refusé",
                "data" => []
            ]);
        }

        // Récupérer les  champs du formulaire (dataAdresses)
        $dataAddresses = $req->getPayload();
                
        $address = new Address();
        $address->setAddressType($dataAddresses->get('address_type'))
                ->setName($dataAddresses->get('name'))
                ->setClientName($dataAddresses->get('client_name'))
                ->setStreet($dataAddresses->get('street'))
                ->setMoreDetails($dataAddresses->get('more_details'))
                ->setZipCode($dataAddresses->get('zip_code'))
                ->setCity($dataAddresses->get('city'))
                ->setState($dataAddresses->get('state'))
                ->setUser($user);

        ;
        $manager->persist($address);
        $manager->flush();

        $addresses = $addressRepository->findByUser($user);

        foreach ($addresses as $key => $address) {
            $address->setUser(null);
            $addresses[$key] = $address;
        }

        return $this->json([
            "isSuccess" => true,
            "data" => $addresses
        ]);
    }

    #[Route('/address/{id}', name: 'app_api_put_address', methods: ['PUT'])]
    public function update(
        $id,
        Request $req,
        AddressRepository $addressRepository, 
        EntityManagerInterface $manager,
    ): Response
    {
        $user = $this->getUser();

        // Si l'user n'es pas connecté, il ne peut pas supprimer d'adresse
        if(!$user){
            return $this->json([
                "isSuccess" => false,
                "message" => "Accès refusé",
                "data" => []
            ]);
        }

        // Rechercher son adresse
        $address = $addressRepository->findOneById($id);
        if(!$address){
            return $this->json([
                "isSuccess" => false,
                "message" => "Adresse introuvable",
                "data" => []
            ]);
        }

        // Vérif si l'adresse appartient bien à celui qui veut la supprimer
        if($user !== $address->getUser()) {
            return $this->json([
                "isSuccess" => false,
                "message" => "Action non autorisée",
                "data" => []
            ]);
        }

        // L'user est connecté et c'est bien son adresse, on peut donc la supprimer en toute sécu
        $manager->flush();

        // Commencer le MAJ
        $dataAddresses = $req->getPayload();
        $address->setAddressType($dataAddresses->get('address_type'))
                ->setName($dataAddresses->get('name'))
                ->setClientName($dataAddresses->get('client_name'))
                ->setStreet($dataAddresses->get('street'))
                ->setMoreDetails($dataAddresses->get('more_details'))
                ->setZipCode($dataAddresses->get('zip_code'))
                ->setCity($dataAddresses->get('city'))
                ->setState($dataAddresses->get('state'))
                ;

        // Demander au manager de preparer cette adresse pour la sauvegarde
        $manager->persist($address);
        $manager->flush();

        // On récupère toutes les adresses
        $addresses = $addressRepository->findByUser($user);
        foreach ($addresses as $key => $address) {
            $address->setUser(null);
            $addresses[$key] = $address;
        }        

        // On les envoie au client
        return $this->json([
            "isSuccess" => true,
            "data" => $addresses
        ]);
    }

    #[Route('/address/{id}', name: 'app_api_delete_address', methods: ['DELETE'])]
    public function delete(
        $id,
        Request $req,
        AddressRepository $addressRepository, 
        EntityManagerInterface $manager,
    ): Response
    {
        $user = $this->getUser();

        // Si l'user n'es pas connecté, il ne peut pas supprimer d'adresse
        if(!$user){
            return $this->json([
                "isSuccess" => false,
                "message" => "Accès refusé",
                "data" => []
            ]);
        }

        $address = $addressRepository->findOneById($id);
        if(!$address){
            return $this->json([
                "isSuccess" => false,
                "message" => "Adresse introuvable",
                "data" => []
            ]);
        }

        // Vérif si l'adresse appartient bien à celui qui veut la supprimer
        if($user !== $address->getUser()) {
            return $this->json([
                "isSuccess" => false,
                "message" => "Action non autorisée",
                "data" => []
            ]);
        }

        // L'user est connecté et c'est bien son adresse, on peut donc la supprimer en toute sécu
        $manager->remove($address);
        $manager->flush();

        $addresses = $addressRepository->findByUser($user);
        foreach ($addresses as $key => $address) {
            $address->setUser(null);
            $addresses[$key] = $address;
        }        

        return $this->json([
            "isSuccess" => true,
            "data" => $addresses
        ]);
    }
}
