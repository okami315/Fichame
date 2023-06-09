<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function checkDNI(string $dni): bool
    {
        $dniLetters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $letter = substr($dni, 0, 8) % 23;
        return substr($dni, -1) == $dniLetters[$letter];
    }
    public function findByUsername($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findByCompany($value): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.company = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

        /**
     * @return User[] Returns an array of User objects
     */
    public function findWithoutRole(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles NOT LIKE :adminRole')
            ->setParameter('adminRole', '%ROLE_ADMIN%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countActiveUsers()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(u)
            FROM App\Entity\User u
            WHERE u.status = 1
            AND u.roles LIKE :role'
        )->setParameter('role', '%ROLE_USER%');
    
        return $query->getSingleScalarResult();
    }
    

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
