<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
#[ApiResource]
class Equipment
{
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

    #[ORM\OneToMany(targetEntity: EquipmentImage::class, mappedBy: "equipment", orphanRemoval: true)]
    /**
     * @var Collection<int, Category>
     */
    private Collection $images;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reference;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
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
     * @return Collection|EquipmentImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(EquipmentImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setEquipment($this);
        }

        return $this;
    }

    public function removeImage(EquipmentImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getEquipment() === $this) {
                $image->setEquipment(null);
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
}
