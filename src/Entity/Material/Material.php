<?php

declare(strict_types=1);

namespace App\Entity\Material;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Material\Booking\GetEstimate;
use App\Entity\Material\Booking\MaterialBooking;
use App\Entity\User;
use App\Repository\MaterialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableMethodsTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampablePropertiesTrait;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaterialRepository::class)]
#[ApiResource(
    itemOperations: [
        'get',
        'get_estimate' => [
            'method' => Request::METHOD_GET,
            'path' => '/material/{id}/booking/estimate',
            'controller' => GetEstimate::class,
            'security' => "is_granted('ROLE_USER')",
            'normalization_context' => ['groups' => 'full'],
        ],
    ],
    attributes: [
        'pagination_type' => 'page'
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['createdAt' => 'DESC', 'name'=> 'ASC'])]
#[ApiFilter(SearchFilter::class, properties: [
    'name' => 'ipartial',
    'brand' => 'ipartial',
    'reference' => 'ipartial',
    'model' => 'ipartial',
    'description' => 'ipartial',
    'category.name' => 'ipartial',
])]
class Material implements TimestampableInterface
{
    use TimestampablePropertiesTrait;
    use TimestampableMethodsTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Assert\Uuid]
    private string $id;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $name;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $brand;

    #[ORM\OneToMany(targetEntity: MaterialImage::class, mappedBy: "material", orphanRemoval: true)]
    /**
     * @var Collection<int, MaterialImage>
     */
    private Collection $images;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reference;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $model;

    #[ORM\ManyToOne(targetEntity: MaterialCategory::class, inversedBy: 'materials')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'materials')]
    private $owner;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    /**
     * @var Collection<int, Pricing>
     */
    #[ORM\OneToMany(mappedBy: 'material', targetEntity: Pricing::class, orphanRemoval: true)]
    private Collection $pricings;

    #[Groups(['full'])]
    #[ORM\OneToMany(mappedBy: 'material', targetEntity: MaterialBooking::class, orphanRemoval: true)]
    private $bookings;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->pricings = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection|MaterialImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(MaterialImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setMaterial($this);
        }

        return $this;
    }

    public function removeImage(MaterialImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getMaterial() === $this) {
                $image->setMaterial(null);
            }
        }

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getCategory(): ?MaterialCategory
    {
        return $this->category;
    }

    public function setCategory(?MaterialCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Pricing[]
     */
    public function getPricings(): Collection
    {
        return $this->pricings;
    }

    /**
     * @return Pricing|null
     */
    public function getPricingByStreak($streak): ?Pricing
    {
        foreach ($this->pricings as $pricing) {
            if ($pricing->getPeriod() === (float) $streak) {
                return $pricing;
            }
        }

        return null;
    }

    public function addPricing(Pricing $pricing): self
    {
        if (!$this->pricings->contains($pricing)) {
            $this->pricings[] = $pricing;
            $pricing->setMaterial($this);
        }

        return $this;
    }

    public function removePricing(Pricing $pricing): self
    {
        if ($this->pricings->removeElement($pricing)) {
            // set the owning side to null (unless already changed)
            if ($pricing->getMaterial() === $this) {
                $pricing->setMaterial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MaterialBooking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(MaterialBooking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setMaterial($this);
        }

        return $this;
    }

    public function removeBooking(MaterialBooking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getMaterial() === $this) {
                $booking->setMaterial(null);
            }
        }

        return $this;
    }
}
