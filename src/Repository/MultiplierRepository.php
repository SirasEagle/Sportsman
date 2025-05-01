<?php

namespace App\Repository;

use App\Entity\Multiplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Multiplier>
 *
 * @method Multiplier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Multiplier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Multiplier[]    findAll()
 * @method Multiplier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MultiplierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Multiplier::class);
    }

//    /**
//     * @return Multiplier[] Returns an array of Multiplier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Multiplier
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
