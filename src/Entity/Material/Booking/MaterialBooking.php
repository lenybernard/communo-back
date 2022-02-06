<?php

namespace App\Entity\Material\Booking;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Material\Booking\GetEstimate;
use App\Controller\Material\Booking\Validate;
use App\Entity\Material\Material;
use App\Entity\User;
use App\Repository\MaterialBookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableMethodsTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampablePropertiesTrait;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaterialBookingRepository::class)]
#[ApiResource(
    itemOperations: [
        'get',
        'validate' => [
            'method' => Request::METHOD_PUT,
            'path' => '/materialbookings/{id}/validate',
            'controller' => Validate::class,
        ],
    ]
)]
class MaterialBooking
{
    use TimestampablePropertiesTrait;
    use TimestampableMethodsTrait;

    public const STATUS_ESTIMATED = 'estimated';
    public const STATUS_VALIDATED = 'validated';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CLOSED = 'closed';
    public const TRANSITION_VALIDATE = 'validate';
    public const TRANSITION_CANCEL = 'cancel';
    public const TRANSITION_CONFIRM = 'confirm';
    public const TRANSITION_CLOSE = 'close';

    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Assert\Uuid]
    #[Groups(['booking'])]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Material::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private Material $material;

    #[Groups(['booking'])]
    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $startDate;

    #[Groups(['booking'])]
    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $endDate;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'materialBookings')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'string', length: 15, options: ["default" => self::STATUS_ESTIMATED])]
    private string $status = self::STATUS_ESTIMATED;

    #[Groups(['booking'])]
    #[ORM\OneToMany(mappedBy: 'booking', targetEntity: MaterialBookingDatePeriod::class, orphanRemoval: true, cascade: ['persist', 'remove'], fetch: 'EAGER')]
    private $periods;

    #[Groups(['booking'])]
    #[ORM\Column(type: 'float', nullable: true)]
    private $price;

    public function __construct(Material $material)
    {
        $this->material = $material;
        $this->periods = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|UserInterface|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return MaterialBookingDatePeriod[]
     */
    public function getPeriods(): array
    {
        return $this->periods->getValues();
    }

    public function addPeriod(MaterialBookingDatePeriod $period): self
    {
        if (!$this->periods->contains($period)) {
            $this->periods[] = $period;
            $period->setBooking($this);
        }

        return $this;
    }

    public function removePeriod(MaterialBookingDatePeriod $period): self
    {
        if ($this->periods->removeElement($period)) {
            // set the owning side to null (unless already changed)
            if ($period->getBooking() === $this) {
                $period->setBooking(null);
            }
        }

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
