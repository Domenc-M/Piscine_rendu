<?php


namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    //OneToMany car on a une catégorie pour plusieurs articles
    //Le premier mot (One) représente l'entité dans laquelle on est, le deuximème (many) représente
    //l'entité à laquelle on la lie
    //mappedBy va de paire avec OneToMany, et représente la relation inverse de OneToMany
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="category")
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La catégorie doit être titulaire d'un titre attitré")
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
     *     minMessage="La catégorie doit être expliquée un minimum",
     *     maxMessage="La description ne peut pas faire plus de 600 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    public function __construct()
    {
        $articles = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}