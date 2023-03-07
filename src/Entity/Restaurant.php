<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Assert\LessThan(51)]
    private ?int $max_people = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxPeople(): ?int
    {
        return $this->max_people;
    }

    public function setMaxPeople(int $max_people): self
    {
        $this->max_people = $max_people;

        return $this;
    }
}
