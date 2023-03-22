<?php

namespace App\Controller\Admin;

use App\Entity\RestaurantWeekdayTimetable;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class RestaurantWeekdayTimetableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RestaurantWeekdayTimetable::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TimeField::new('openam', 'Ouverture matin'),
            TimeField::new('closeam', 'Fermeture matin'),
            TimeField::new('openpm', 'Ouverture soir'),
            TimeField::new('closepm', 'fermeture soir'),
        ];
    }
}
