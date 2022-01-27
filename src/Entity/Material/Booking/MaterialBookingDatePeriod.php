<?php

namespace App\Entity\Material\Booking;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource]
class MaterialBookingDatePeriod
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Assert\Uuid]
    private string $id;

    #[Groups(['booking'])]
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $startDate;

    #[Groups(['booking'])]
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $endDate;

    #[ORM\ManyToOne(targetEntity: MaterialBooking::class, inversedBy: 'periods')]
    #[ORM\JoinColumn(nullable: false)]
    private MaterialBooking $booking;

    #[Groups(['booking'])]
    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $price;

    public function __construct(MaterialBooking $booking, \DateTime $startDate, \DateTime $endDate)
    {
        $this->booking = $booking;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStartDate(): DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getBooking(): MaterialBooking
    {
        return $this->booking;
    }

    public function setBooking(?MaterialBooking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
