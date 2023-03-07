<?php

namespace App\DataFixtures;

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
        for ($i = 0; $i < 21; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->name());
            $user->setSecondName($this->faker->lastName());
            $user->setEmail($this->faker->email());
            $user->setPassword('E#NpeQm&5h9#PeL3');
            $user->setPeoplePref($this->faker->randomDigitNot(0));

            $manager->persist($user);
        }




        $manager->flush();
    }
}
