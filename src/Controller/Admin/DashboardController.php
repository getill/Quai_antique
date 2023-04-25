<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Restaurant;
use App\Entity\RestaurantWeekdayTimetable;
use App\Entity\Reservation;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin.index')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Quai Antique - Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Restaurant', 'fa fa-home', Restaurant::class);
        yield MenuItem::linkToCrud('Horaires d\'ouverture', 'fa-regular fa-clock', RestaurantWeekdayTimetable::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-users', User::class);
        yield MenuItem::linkToCrud('Réservation', 'fa-regular fa-calendar', Reservation::class);
        yield MenuItem::linkToCrud('La carte', 'fa-solid fa-utensils', Menu::class);
    }
}
