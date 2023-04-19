<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class MenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Quai antique - La carte');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('description')->setFormTypeOptions([
                'attr' => [
                    'maxlength' => 100
                ]
            ]),
            IntegerField::new('price')->setFormTypeOptions([
                'attr' => [
                    'min' => 0
                ]
            ]),
            BooleanField::new('selected'),
            TextField::new('img_title')->onlyOnForms(),
            ImageField::new('img')
                ->setBasePath('/assets/img/dishes')
                ->setRequired(true)
                ->setUploadDir('public/assets/img/dishes'),
            AssociationField::new('category')
        ];
    }
}
