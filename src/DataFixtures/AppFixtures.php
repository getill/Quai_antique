<?php

namespace App\DataFixtures;

use Faker\Generator;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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


            $manager->persist($user);
        }





        $manager->flush();
    }
}
