<?php

namespace App\Entity\Material;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Controller\Material\Booking\GetEstimate;
use App\Entity\User\Circle;
use App\Repository\PricingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PricingRepository::class)]
#[ApiResource(
    itemOperations: [
        'get',
    ],
    attributes: [
        'pagination_type' => 'page'
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['value' => 'ASC'])]
class Pricing
{
    const PRICING_HALF = 0.5;
    const PRICING_DAY = 1;
    const PRICING_DOUBLE = 2;
    const PRICING_WEEK = 7;
    const PRICING_MONTH = 31;

    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Assert\Uuid]
    private string $id;

    #[ORM\Column(type: 'float')]
    private float $value;

    #[ORM\Column(type: 'float')]
    private float $period = self::PRICING_DAY;

    #[ORM\ManyToOne(targetEntity: Material::class, inversedBy: 'pricings')]
    #[ORM\JoinColumn(nullable: false)]
    private Material $material;

    /**
     * @var Collection<int, Circle>
     */
    #[ORM\ManyToMany(targetEntity: Circle::class, inversedBy: 'pricings')]
    private Collection $circles;

    public function __construct()
    {
        $this->materials = new ArrayCollection();
        $this->circles = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getPeriod(): float
    {
        return $this->period;
    }

    public function setPeriod(float $period): self
    {
        $this->period = $period;

        return $this;
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

    /**
     * @return Collection|Circle[]
     */
    public function getCircles(): Collection
    {
        return $this->circles;
    }

    public function addCircle(Circle $circle): self
    {
        if (!$this->circles->contains($circle)) {
            $this->circles[] = $circle;
        }

        return $this;
    }

    public function removeCircle(Circle $circle): self
    {
        $this->circles->removeElement($circle);

        return $this;
    }
}
