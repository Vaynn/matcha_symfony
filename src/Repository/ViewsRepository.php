<?php

namespace App\Repository;

use App\Entity\Views;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Views>
 *
 * @method Views|null find($id, $lockMode = null, $lockVersion = null)
 * @method Views|null findOneBy(array $criteria, array $orderBy = null)
 * @method Views[]    findAll()
 * @method Views[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Views::class);
    }

//    /**
//     * @return Views[] Returns an array of Views objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Views
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
