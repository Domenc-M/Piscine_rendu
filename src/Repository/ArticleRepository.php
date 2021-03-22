<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //Rajout de methode pour rechercher mot specifique dans Content
    public function findByTerm(string $search)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        //On utilise l'objet QueryBuilder pour generer une requete SQL
        //Puisque les methode renvoient l'objet, on peut l'appeler plusieurs fois de suite sans répétition
        //C'est le Fluent.
        $query = $queryBuilder
            //Alias utilise
            ->select('a')
            //Ciblage de la colonne et du mot a chercher
            ->where('a.content LIKE :search')
            ->orWhere('a.title LIKE :search')
            //Securite, on met une etape de plus pour eviter l'injection SQL
            ->setParameter('search', '%'.$search.'%')
            //On renvoie la query, qui est donc stockee dans $query
            ->getQuery();

        //On retourne le résultat
        return $query->getResult();

    }
    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
