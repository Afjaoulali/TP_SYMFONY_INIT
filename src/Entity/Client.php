<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresse;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    private $zipcode;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Employes::class)]
    private $employes;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return Collection<int, Employes>
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employes $employe): self
    {
        if (!$this->employes->contains($employe)) {
            $this->employes[] = $employe;
            $employe->setClient($this);
        }

        return $this;
    }

    public function removeEmploye(Employes $employe): self
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getClient() === $this) {
                $employe->setClient(null);
            }
        }

        return $this;
    }
}
