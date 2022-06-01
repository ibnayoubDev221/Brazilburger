<?php

namespace App\Entity;

use App\Repository\BurgerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
class Burger
{

    public function _construct(){
        
        $this->etat = "non_archiver";
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'string', length: 255)]
    public $nom;

    #[ORM\Column(type: 'integer')]
    public $prix;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public $etat;

    #[ORM\OneToOne(inversedBy: 'burger', targetEntity: Complement::class, cascade: ['persist', 'remove'])]
    private $complements;

    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: Image::class, cascade: ["persist"])]
    public $images;

    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: Menu::class)]
    public $menus;

  

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\ManyToMany(targetEntity: Commande::class, inversedBy: 'burgers')]
    private $commandes;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $type;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->type = 'burger';
       
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getComplements(): ?Complement
    {
        return $this->complements;
    }

    public function setComplements(?Complement $complements): self
    {
        $this->complements = $complements;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setBurger($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBurger() === $this) {
                $image->setBurger(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setBurger($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getBurger() === $this) {
                $menu->setBurger(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
 




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
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        $this->commandes->removeElement($commande);

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
