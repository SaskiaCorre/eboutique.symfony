<?php

namespace App\Controller\Admin;

use App\Entity\Sliders;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SlidersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sliders::class;
    }
   
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('description'),
            TextField::new('button_text'),
            TextField::new('button_link'),
            ImageField::new('imageUrl')
            ->setUploadDir('public/assets/images/sliders') // Chemin relatif au projet - NB: ic, par contre, j'ai besoin de products != category
            ->setBasePath('assets/images/sliders') // Chemin pour afficher l'image dans l'admin
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired($pageName === Crud::PAGE_NEW),
        ];
    }
    
}
