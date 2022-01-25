<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plans
 *
 * @ORM\Table(name="plans")
 * @ORM\Entity(repositoryClass=PlanRepository::class)
 */
class Plans
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="User", type="string", length=255, nullable=true)
     */
    private $user;

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

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }


}
