<?php

namespace App\Repository;

use App\Entity\Gender;
use App\Entity\Preferences;
use App\Entity\Sexuality;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $registry;
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
        $this->registry = $registry;
        $this->paginator = $paginator;

    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByPreferences(Preferences $preferences, Request $request){
        if (!$preferences->getGenders()->toArray()){
            $genderRepository = $this->registry->getRepository(Gender::class);
            $genders = $genderRepository->findAll();
        }
        else
            $genders = $preferences->getGenders()->toArray();
        if (!$preferences->getSexualities()->toArray()){
            $sexualityRepository = $this->registry->getRepository(Sexuality::class);
            $sexualities = $sexualityRepository->findAll();
        }
        else
            $sexualities = $preferences->getSexualities()->toArray();
        $qb = $this->createQueryBuilder('u')
            ->where('u.age >= :min_age')
            ->andWhere('u.age <= :max_age')
            ->andWhere('u.gender IN (:genders)')
            ->andWhere('u.sexuality IN (:sexualities)')
            ->setParameters([
                'min_age' => $preferences->getMinAge(),
                'max_age'=> $preferences->getMaxAge(),
                'genders'=> $genders,
                'sexualities'=> $sexualities
            ]);
        $query = $qb->getQuery();
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );
        return $pagination;

    }

    public function findAllExceptCurrentUser($currentUserId, Request $request){
        $qb = $this->createQueryBuilder('u')
            ->where('u.id != :currentUserId')
            ->setParameter('currentUserId', $currentUserId);
        $query = $qb->getQuery();
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );
        return $pagination;

    }

//    /**
//     * @return User[] Returns an array of User objects
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

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
