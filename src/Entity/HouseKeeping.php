<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\HouseKeepingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=HouseKeepingRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups": {"read:housecollection"}},
 *      collectionOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/house_keepings",
 *             "openapi_context"={
 *                  "summary"= "Voir les menages"
 *              }
 *          }
 *      },
 *      itemOperations={
 *         "get"={
 *            "method"="GET",
 *             "path"="/house_keepings/{id}",
 *             "openapi_context"={
 *                  "summary"= "Voir une menage"
 *              }
 *          }
 *      }
 * )
 */
class HouseKeeping
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"read:productor:house_keeping","read:housecollection"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:productor:house_keeping","read:housecollection"})
     * @Assert\NotNull
     * @Assert\Type("string")
     */
    private $NIM;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type("string")
     * @Groups({"read:productor:house_keeping","read:housecollection"})
     */
    private $reference;

    /**
     * @ORM\OneToMany(targetEntity=Productor::class, mappedBy="housekeeping")
     */
    private $productors;

    /**
     * @Assert\NotNull
     * @Groups({"read:productor:house_keeping","read:housecollection"})
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     */
    private $address;

    public function __construct()
    {
        $this->productors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /*
    * @Groups({"read:agriculcollection"})
    */
    public function getIri(): string
    {
        return '/api/house_keepings/'. $this->id;
    }


    public function getNIM(): ?string
    {
        return $this->NIM;
    }

    public function setNIM(string $NIM): self
    {
        $this->NIM = $NIM;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection|Productor[]
     */
    public function getProductors(): Collection
    {
        return $this->productors;
    }

    public function addProductor(Productor $productor): self
    {
        if (!$this->productors->contains($productor)) {
            $this->productors[] = $productor;
            $productor->setHousekeeping($this);
        }

        return $this;
    }

    public function removeProductor(Productor $productor): self
    {
        if ($this->productors->removeElement($productor)) {
            // set the owning side to null (unless already changed)
            if ($productor->getHousekeeping() === $this) {
                $productor->setHousekeeping(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }
}
