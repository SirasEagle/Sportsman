<?php

namespace App\Repository;

use App\Entity\UserLastMuscleGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserLastMuscleGroup>
 *
 * @method UserLastMuscleGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLastMuscleGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLastMuscleGroup[]    findAll()
 * @method UserLastMuscleGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLastMuscleGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLastMuscleGroup::class);
    }

    public function save(UserLastMuscleGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserLastMuscleGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UserLastMuscleGroup[] Returns an array of UserLastMuscleGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserLastMuscleGroup
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
