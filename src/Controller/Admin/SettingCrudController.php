<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
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
            TextField::new('website_name'),
            TextField::new('description')->hideOnIndex(),
            EmailField::new('email'),
            IntegerField::new('taxe_rate'),
            ImageField::new('logo')
            ->setUploadDir('public/assets/images/setting') // Chemin relatif au projet - NB: dans le cours Udemy, il rajoute /categories sur les 2 chemins, mais ça ne fonctionne pas chez moi
            ->setBasePath('assets/images/setting') // Chemin pour afficher l'image dans l'admin
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired($pageName === Crud:: PAGE_NEW)
            ,
            ChoiceField::new('currency')->setChoices([
                'EUR' => 'EUR',
                'USD' => 'USD',
                'XOF' => 'XOF'
            ]),
            TextField::new('facebookLink')->hideOnIndex(),
            TextField::new('instagramLink')->hideOnIndex(),
            TextField::new('youtubeLink')->hideOnIndex(),
            TelephoneField::new('phone'),
            TextField::new('street'),
            TextField::new('city'),
            TextField::new('code_postal'),
            TextField::new('state'),
            TextField::new('copyright')->hideOnIndex(),
        ];
    }
    
}
