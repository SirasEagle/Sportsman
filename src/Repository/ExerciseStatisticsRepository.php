<?php

namespace App\Repository;

use App\Entity\ExerciseStatistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExerciseStatistics>
 *
 * @method ExerciseStatistics|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciseStatistics|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciseStatistics[]    findAll()
 * @method ExerciseStatistics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseStatisticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciseStatistics::class);
    }

//    /**
//     * @return ExerciseStatistics[] Returns an array of ExerciseStatistics objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExerciseStatistics
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
