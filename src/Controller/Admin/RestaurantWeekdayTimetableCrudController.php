<?php

namespace App\Controller\Admin;

use App\Entity\RestaurantWeekdayTimetable;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


class RestaurantWeekdayTimetableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RestaurantWeekdayTimetable::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Quai antique - Horaires d\'ouverture');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('NameWeekday', 'Jour de la semaine')
                ->setFormTypeOption('disabled', 'disabled'),
            TimeField::new('openam', 'Ouverture matin'),
            TimeField::new('closeam', 'Fermeture matin'),
            TimeField::new('openpm', 'Ouverture soir'),
            TimeField::new('closepm', 'fermeture soir'),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
}
