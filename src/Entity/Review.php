<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @Groups("review")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("review")
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\PositiveOrZero
     * @Assert\Length(max = 2)
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $overall;

    /**
     * @Groups("review")
     * @Assert\Length(max = 1000, min = 5)
     * @ORM\Column(type="text", nullable=true)
     */
    private $feedback;

    /**
     * @Groups("review")
     * @Assert\PositiveOrZero
     * @Assert\Length(max = 2)
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $quality;

    /**
     * @Groups("review")
     * @Assert\PositiveOrZero
     * @Assert\NotBlank
     * @Assert\Length(max = 2)
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $communication;

    /**
     * @Groups("review")
     * @Assert\PositiveOrZero
     * @Assert\Length(max = 2)
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $price;

    /**
     * @Groups("project")
     * @Assert\NotBlank
     * @Assert\NotNull
     * @ORM\OneToOne(targetEntity=Project::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOverall(): ?int
    {
        return $this->overall;
    }

    public function setOverall(int $overall): self
    {
        $this->overall = $overall;

        return $this;
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(?string $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }

    public function setQuality(?int $quality): self
    {
        $this->quality = $quality;

        return $this;
    }

    public function getCommunication(): ?int
    {
        return $this->communication;
    }

    public function setCommunication(?int $communication): self
    {
        $this->communication = $communication;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
