<?php

namespace App\Repository;

use App\Entity\NutritionalTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NutritionalTable>
 *
 * @method NutritionalTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method NutritionalTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method NutritionalTable[]    findAll()
 * @method NutritionalTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NutritionalTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NutritionalTable::class);
    }

//    /**
//     * @return NutritionalTable[] Returns an array of NutritionalTable objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NutritionalTable
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
