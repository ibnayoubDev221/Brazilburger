<?php

namespace App\Entity;

use App\Repository\PayementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PayementRepository::class)]
class Payement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $motant;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $date;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'payements')]
    private $clients;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotant(): ?int
    {
        return $this->motant;
    }

    public function setMotant(?int $motant): self
    {
        $this->motant = $motant;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getClients(): ?Client
    {
        return $this->clients;
    }

    public function setClients(?Client $clients): self
    {
        $this->clients = $clients;

        return $this;
    }
}
