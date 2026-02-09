<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions): Actions{
        return $actions
        ->add(Crud::PAGE_EDIT, Action::INDEX)
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->add(Crud::PAGE_EDIT, Action::DETAIL)
        ;    
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name')->hideOnIndex(), // pas pour les afficher mais pour les créer dynamiquement
            TextField::new('description')->hideOnIndex(),
            TextEditorField::new('more_description')->hideOnIndex(),
            TextEditorField::new('additional_info')->hideOnIndex(),
            AssociationField::new('relatedProducts')->hideOnIndex(),
            ImageField::new('imageUrls', 'Images')
                ->setFormTypeOptions([
                    'multiple' => true,
                    'attr' => [
                        'accept' => 'image/*'
                    ]
                ])
                ->setUploadDir('public/assets/images/products') // Chemin relatif au projet - NB: ic, par contre, j'ai besoin de products != category
                ->setBasePath('assets/images/products') // Chemin pour afficher l'image dans l'admin
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired($pageName === Crud::PAGE_NEW),
            MoneyField::new('solde_price')->setCurrency("EUR")->setStoredAsCents(false),
            MoneyField::new('regular_price')->setCurrency("EUR")->setStoredAsCents(false),
            IntegerField::new('stock'),
            AssociationField::new('categories'),
            BooleanField::new('isBestSeller'),
            BooleanField::new('isNewArrival'),
            BooleanField::new('isFeatured'),
            BooleanField::new('isSpecialOffer'),
        ];
    }
}
