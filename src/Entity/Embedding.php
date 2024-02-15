<?php

namespace App\Entity;

use App\Repository\EmbeddingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmbeddingRepository::class)]
class Embedding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $value = null;

    #[ORM\ManyToOne(inversedBy: 'embeddings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Word $ofWord = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getOfWord(): ?Word
    {
        return $this->ofWord;
    }

    public function setOfWord(?Word $ofWord): static
    {
        $this->ofWord = $ofWord;

        return $this;
    }
}
