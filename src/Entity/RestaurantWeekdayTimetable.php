<?php

namespace App\Entity;

use App\Repository\RestaurantWeekdayTimetableRepository;
use Container5yMYd82\getNumberConfiguratorService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantWeekdayTimetableRepository::class)]
class RestaurantWeekdayTimetable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $open_am = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $close_am = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $open_pm = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $close_pm = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?RestaurantWeekday $weekday_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenAm(): ?\DateTimeInterface
    {
        return $this->open_am;
    }

    public function setOpenAm(\DateTimeInterface $open_am): self
    {
        $this->open_am = $open_am;

        return $this;
    }

    public function getCloseAm(): ?\DateTimeInterface
    {
        return $this->close_am;
    }

    public function setCloseAm(\DateTimeInterface $close_am): self
    {
        $this->close_am = $close_am;

        return $this;
    }

    public function getOpenPm(): ?\DateTimeInterface
    {
        return $this->open_pm;
    }

    public function setOpenPm(\DateTimeInterface $open_pm): self
    {
        $this->open_pm = $open_pm;

        return $this;
    }

    public function getClosePm(): ?\DateTimeInterface
    {
        return $this->close_pm;
    }

    public function setClosePm(\DateTimeInterface $close_pm): self
    {
        $this->close_pm = $close_pm;

        return $this;
    }

    public function getNameWeekday(): ?string
    {
        return $this->weekday_id->getName();
    }
    public function getIdWeekday(): ?RestaurantWeekday
    {
        return $this->weekday_id;
    }

    public function setIdWeekday(RestaurantWeekday $weekday_id): self
    {
        $this->weekday_id = $weekday_id;

        return $this;
    }
}