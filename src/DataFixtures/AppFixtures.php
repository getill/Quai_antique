<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Menu;
use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Entity\RestaurantWeekday;
use App\Entity\RestaurantWeekdayTimetable;
use Faker\Generator;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    //----------------- Categories creator -----------------

    public function createCategory(string $name, ObjectManager $manager)
    {
        $categories = new Categories();
        $categories->setName($name);
        $manager->persist($categories);

        return $categories;
    }

    //----------------- Dishes creator -----------------

    public function createDishes(string $name, string $description, string $img, string $imgTitle, int $selected, int $price, ObjectManager $manager, Categories $category)
    {
        $dishes = new Menu();
        $dishes->setName($name)
            ->setDescription($description)
            ->setImg($img)
            ->setImgTitle($imgTitle)
            ->setSelected($selected)
            ->setCategory($category)
            ->setPrice($price);
        $manager->persist($dishes);

        return $dishes;
    }

    //----------------- Weekday creator -----------------

    public function createWeekday(string $name, ObjectManager $manager)
    {
        $weekday = new RestaurantWeekday();
        $weekday->setName($name);
        $manager->persist($weekday);

        return $weekday;
    }

    //----------------- Weekday Timetable creator -----------------

    public function createWeekdayTimetable(RestaurantWeekday $weekday, int $isclosed, ObjectManager $manager)
    {
        $dt1 = "2022-04-07 11:30:00";
        $date1 = \DateTime::createFromFormat("Y-m-d H:i:s", $dt1);
        $dt2 = "2022-04-07 14:30:00";
        $date2 = \DateTime::createFromFormat("Y-m-d H:i:s", $dt2);
        $dt3 = "2022-04-07 19:30:00";
        $date3 = \DateTime::createFromFormat("Y-m-d H:i:s", $dt3);
        $dt4 = "2022-04-07 21:00:00";
        $date4 = \DateTime::createFromFormat("Y-m-d H:i:s", $dt4);

        $weekdayTime = new RestaurantWeekdayTimetable();
        $weekdayTime->setIdWeekday($weekday)
            ->setOpenAm($date1)
            ->setCloseAm($date2)
            ->setOpenPm($date3)
            ->setClosePm($date4)
            ->setIsClosed($isclosed);
        $manager->persist($weekdayTime);

        return $weekdayTime;
    }


    public function load(ObjectManager $manager): void
    {
        // Category
        $categoryEntree = $this->createCategory('Entrée', $manager);
        $categoryPlat = $this->createCategory('Plat', $manager);
        $categoryDessert = $this->createCategory('Dessert', $manager);

        // Dishes
        $this->createDishes(
            'Yaourts Aux Fruits La Fraîche Altitude',
            'Nos yaourts de Savoie "La Fraîche Altitude" sur lit de fruit sont fabriqués dans nos ateliers de Flumet',
            'yaourts-aux-fruits-x4.jpg',
            'Yaourts Aux Fruits La Fraîche Altitude',
            1,
            23,
            $manager,
            $categoryDessert

        );
        $this->createDishes(
            'Terrine à l\'Abondance AOP',
            'Terrine à l\'abondance AOP, fabriquée à Doussard, en Haute-Savoie au bord du lac d\'Annecy.',
            'terrine-a-l-abondance.jpg',
            'Terrine à l\'Abondance AOP',
            1,
            38,
            $manager,
            $categoryEntree
        );
        $this->createDishes(
            'La raclette antique',
            'La raclette est assurément le plat Savoyard le plus connu et le plus apprécié pour ses saveurs mais aussi pour son côté convivial!',
            'recette-raclette.jpg.webp',
            'La raclette antique',
            1,
            10,
            $manager,
            $categoryPlat
        );

        // Weekday
        $lundi = $this->createWeekday('Lundi', $manager);
        $mardi = $this->createWeekday('Mardi', $manager);
        $mercredi = $this->createWeekday('Mercredi', $manager);
        $jeudi = $this->createWeekday('Jeudi', $manager);
        $vendredi = $this->createWeekday('Vendredi', $manager);
        $samedi = $this->createWeekday('Samedi', $manager);
        $dimanche = $this->createWeekday('Dimanche', $manager);

        // Weekday Timetable
        $this->createWeekdayTimetable($lundi, 1, $manager);
        $this->createWeekdayTimetable($mardi, 0, $manager);
        $this->createWeekdayTimetable($mercredi, 0, $manager);
        $this->createWeekdayTimetable($jeudi, 0, $manager);
        $this->createWeekdayTimetable($vendredi, 0, $manager);
        $this->createWeekdayTimetable($samedi, 0, $manager);
        $this->createWeekdayTimetable($dimanche, 1, $manager);

        // Restaurant
        $restaurant = new Restaurant();
        $restaurant->setMaxPeople(50);
        $manager->persist($restaurant);

        // Users Generator
        for ($i = 0; $i < 21; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->name())
                ->setSecondName($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPeoplePref(mt_rand(0, 1) === 1 ? $this->faker->randomDigitNot(0) : null)
                ->setPlainPassword('E#NpeQm&5h9#PeL3');

            $users[] = $user;
            $manager->persist($user);
        }

        // Admin Generator
        $userAdmin = new User;
        $userAdmin->setFirstName($this->faker->name())
            ->setSecondName($this->faker->lastName())
            ->setEmail($this->faker->email())
            ->setRoles(['ROLE_ADMIN'])
            ->setPeoplePref(mt_rand(0, 1) === 1 ? $this->faker->randomDigitNot(0) : null)
            ->setPlainPassword('E#NpeQm&5h9#PeL3');
        $manager->persist($userAdmin);

        // Reservation Generator
        for ($i = 0; $i < 25; $i++) {
            $reservation = new Reservation();
            $reservation->setNbPeople(mt_rand(0, 13))
                ->setDateTime($this->faker->dateTimeBetween('+1 week ', '+2 weeks'))
                ->setUser($users[mt_rand(0, count($users) - 1)]);


            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
