<?php

namespace App\Entity\Material\Booking;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Material\Booking\GetRatings;
use App\Entity\User;
use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableMethodsTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampablePropertiesTrait;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ApiResource]
class Rating implements TimestampableInterface
{
    use TimestampablePropertiesTrait;
    use TimestampableMethodsTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Assert\Uuid]
    private string $id;

    #[ORM\Column(type: 'smallint')]
    private $value;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'ratingsSent')]
    private $author;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: MaterialBooking::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $booking;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBooking(): ?MaterialBooking
    {
        return $this->booking;
    }

    public function setBooking(?MaterialBooking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }
}
