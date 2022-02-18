<?php

namespace App\Entity\User;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Material\Pricing;
use App\Entity\User;
use App\Repository\User\CircleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableMethodsTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampablePropertiesTrait;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable()
 */
#[ORM\Entity(repositoryClass: CircleRepository::class)]
#[ApiResource]
class Circle implements TimestampableInterface
{
    use TimestampablePropertiesTrait;
    use TimestampableMethodsTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Assert\Uuid]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = '';

    /**
     * @Vich\UploadableField(mapping="circle_logo", fileNameProperty="logoName", size="logoSize")
     */
    private ?File $logoFile = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $logoName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $logoSize = null;

    /**
     * @var Collection<int, User>|null
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'circles')]
    private ?Collection $members;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?Circle $parent = null;

    /**
     * @var Collection<int, Circle>|null
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private ?Collection $children;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $city;

    #[ORM\ManyToMany(targetEntity: Pricing::class, mappedBy: 'circles')]
    private $pricings;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->pricings = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    /**
     * @param array<int, User> $children
     */
    public function setMembers(array $members): self
    {
        foreach ($members as $member) {
            $this->addMember($member);
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @param array<int, Circle> $children
     */
    public function setChildren(array $children): self
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $logoFile
     */
    public function setLogoFile(?File $logoFile = null): void
    {
        $this->logoFile = $logoFile;
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoName(?string $logoName): void
    {
        $this->logoName = $logoName;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoSize(?int $logoSize): void
    {
        $this->logoSize = $logoSize;
    }

    public function getLogoSize(): ?int
    {
        return $this->logoSize;
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Pricing[]
     */
    public function getPricings(): Collection
    {
        return $this->pricings;
    }

    public function addPricing(Pricing $pricing): self
    {
        if (!$this->pricings->contains($pricing)) {
            $this->pricings[] = $pricing;
            $pricing->addCircle($this);
        }

        return $this;
    }

    public function removePricing(Pricing $pricing): self
    {
        if ($this->pricings->removeElement($pricing)) {
            $pricing->removeCircle($this);
        }

        return $this;
    }
}
