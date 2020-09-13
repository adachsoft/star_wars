<?php

namespace App\Entity;

use App\Repository\CharactersRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=CharactersRepository::class)
 */
class Characters
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Episodes", inversedBy="characters")
     */
    private $episodes;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Planet", inversedBy="characters")
     */
    private $planet;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Characters", inversedBy="characters")
     */
    private $friends;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
        $this->friends = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function addEpisode(Episodes $episode): void
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes[] = $episode;
        }
    }

    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function getPlanet(): ?Planet
    {
        return $this->planet;
    }

    public function setPlanet(?Planet $planet): void
    {
        $this->planet = $planet;
    }

    public function addFriend(Characters $friend): void
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
        }
    }

    public function getFriends(): Collection
    {
        return $this->friends;
    }
}
