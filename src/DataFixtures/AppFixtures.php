<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
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

    public function load(ObjectManager $manager): void
    {
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

        for ($i = 0; $i < 25; $i++) {
            $reservation = new Reservation();
            $reservation->setNbPeople(mt_rand(0, 13))
                ->setDateTime($this->faker->dateTime())
                ->setUser($users[mt_rand(0, count($users) - 1)]);


            $manager->persist($reservation);
        }




        $manager->flush();
    }
}
