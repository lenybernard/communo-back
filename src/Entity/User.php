<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Material\Booking\MaterialBooking;
use App\Entity\Material\Booking\Rating;
use App\Entity\Material\Material;
use App\Entity\User\Circle;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableMethodsTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampablePropertiesTrait;
use libphonenumber\PhoneNumber;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface, TimestampableInterface
{
    use TimestampablePropertiesTrait;
    use TimestampableMethodsTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Assert\Uuid]
    private string $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['full'])]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;
    private $plainPassword;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Material::class)]
    private $materials;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['full'])]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['full'])]
    private $lastname;

    #[ORM\Column(type: 'phone_number', nullable: true)]
    #[Groups(['full'])]
    private $phoneNumberObject;

    public string $phoneNumber;

    /**
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatarName", size="avatarSize")
     */
    private ?File $avatarFile = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $avatarName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $avatarSize = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $city;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MaterialBooking::class, orphanRemoval: true)]
    private $materialBookings;

    #[ORM\ManyToMany(targetEntity: Circle::class, mappedBy: 'members')]
    private $circles;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Rating::class)]
    private $ratingsSent;

    #[ApiSubresource]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Rating::class, orphanRemoval: true)]
    private $ratings;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $gender;

    public function __construct()
    {
        $this->materials = new ArrayCollection();
        $this->materialBookings = new ArrayCollection();
        $this->circles = new ArrayCollection();
        $this->ratingsSent = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Material[]
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Material $material): self
    {
        if (!$this->materials->contains($material)) {
            $this->materials[] = $material;
            $material->setOwner($this);
        }

        return $this;
    }

    public function removeMaterial(Material $material): self
    {
        if ($this->materials->removeElement($material)) {
            // set the owning side to null (unless already changed)
            if ($material->getOwner() === $this) {
                $material->setOwner(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoneNumberObject(): PhoneNumber
    {
        return $this->phoneNumberObject;
    }

    public function setPhoneNumberObject(PhoneNumber $phoneNumberObject): self
    {
        $this->phoneNumberObject = $phoneNumberObject;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        $prefix = "";
        for ($i = 0; $i < $this->getPhoneNumberObject()->getNumberOfLeadingZeros(); $i++) {
            $prefix .= "0";
        }
        return $prefix.$this->getPhoneNumberObject()->getNationalNumber();
    }
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $avatarFile
     */
    public function setAvatarFile(?File $avatarFile = null): void
    {
        $this->avatarFile = $avatarFile;
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function setAvatarName(?string $avatarName): void
    {
        $this->avatarName = $avatarName;
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    public function setAvatarSize(?int $avatarSize): void
    {
        $this->avatarSize = $avatarSize;
    }

    public function getAvatarSize(): ?int
    {
        return $this->avatarSize;
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
     * @return Collection|MaterialBooking[]
     */
    public function getMaterialBookings(): Collection
    {
        return $this->materialBookings;
    }

    public function addMaterialBooking(MaterialBooking $materialBooking): self
    {
        if (!$this->materialBookings->contains($materialBooking)) {
            $this->materialBookings[] = $materialBooking;
            $materialBooking->setUser($this);
        }

        return $this;
    }

    public function removeMaterialBooking(MaterialBooking $materialBooking): self
    {
        if ($this->materialBookings->removeElement($materialBooking)) {
            // set the owning side to null (unless already changed)
            if ($materialBooking->getUser() === $this) {
                $materialBooking->setUser(null);
            }
        }

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
            $circle->addMember($this);
        }

        return $this;
    }

    public function removeCircle(Circle $circle): self
    {
        if ($this->circles->removeElement($circle)) {
            $circle->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatingsSent(): Collection
    {
        return $this->ratingsSent;
    }

    public function addRatingsSent(Rating $ratingsSent): self
    {
        if (!$this->ratingsSent->contains($ratingsSent)) {
            $this->ratingsSent[] = $ratingsSent;
            $ratingsSent->setAuthor($this);
        }

        return $this;
    }

    public function removeRatingsSent(Rating $ratingsSent): self
    {
        if ($this->ratingsSent->removeElement($ratingsSent)) {
            // set the owning side to null (unless already changed)
            if ($ratingsSent->getAuthor() === $this) {
                $ratingsSent->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function getAverageRatingScore(): float|null
    {
        $ratingsNumber = $this->ratings->count();

        if (0 === $ratingsNumber)
            return null;

        $scores = $this->ratings->map(function($rating) { return $rating->getValue(); })->getValues();

        return array_sum($scores) / $ratingsNumber;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setRatedUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getRatedUser() === $this) {
                $rating->setRatedUser(null);
            }
        }

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
}
