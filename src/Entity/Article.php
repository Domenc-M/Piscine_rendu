<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    //La cle primaire est la colonne ID.
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'article doit être titulaire d'un titre attitré.")
     * @Assert\Length(
     *     max=100,
     *     maxMessage="Le titre ne peut pas faire plus de 100 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *     min=10,
     *     max=600,
     *     minMessage="Le contenu doit faire au moins 10 caractères",
     *     maxMessage="Le contenu ne peut pas faire plus de 600 caractères")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    //On indique quelle entité contiens la clé étrangère, et laquelle contient la relation inverse
    //ManyToOne car on a plusieurs articles pour une seule catégorie
    //Le premier mot (many) représente l'entité dans laquelle on est, le deuxième (one) représente
    //l'entité à laquelle on la lie
    //inversedBy va de paire avec ManyToOne, et représente la relation inverse de OneToMany
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
}
