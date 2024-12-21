<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Récupère les 3 catégories ayant le plus de topics
     *
     * @return Category[]
     */
    public function findTopCategoriesByTopics(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.topics', 't')  // Effectuer une jointure sur les topics
            ->groupBy('c.id')            // Grouper les résultats par catégorie
            ->orderBy('COUNT(t.id)', 'DESC') // Trier par nombre de topics décroissant
            ->setMaxResults(3)           // Limiter à 3 catégories
            ->getQuery()
            ->getResult();               // Récupérer uniquement les entités Category
    }

    /**
     * Récupère tous les topics associés à une catégorie donnée par son nom
     *
     * @param string $name Le nom de la catégorie
     * @return Topic[] Retourne tous les topics associés à cette catégorie
     */
    public function findAllTopicsByCategory(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.topics', 't')  // Jointure avec l'entité Topic
            ->where('c.name = :name')    // Filtrer les catégories par nom
            ->setParameter('name', $name) // Définir la valeur du paramètre :name
            ->getQuery()
            ->getResult(); // Récupère les topics associés à cette catégorie
    }
}
