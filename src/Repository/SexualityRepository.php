<?php

namespace App\Repository;

use App\Entity\Sexuality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sexuality>
 *
 * @method Sexuality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sexuality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sexuality[]    findAll()
 * @method Sexuality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SexualityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sexuality::class);
    }

//    /**
//     * @return Sexuality[] Returns an array of Sexuality objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sexuality
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
