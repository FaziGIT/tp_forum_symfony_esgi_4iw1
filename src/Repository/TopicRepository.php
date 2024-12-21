<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Topic>
 */
class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    //    /**
    //     * @return Topic[] Returns an array of Topic objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Topic
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findInOneRequest(Topic $topic)
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'c', 'cat', 'u', 'l')
            ->leftJoin('t.responses', 'c')
            ->leftJoin('t.categories', 'cat')
            ->leftJoin('t.user', 'u')
            ->leftJoin('t.langue', 'l')
            ->andWhere('t.id = :id')
            ->setParameter('id', $topic->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
