<?php

namespace App\Repository;

use App\Entity\Signing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Signing>
 *
 * @method Signing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Signing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Signing[]    findAll()
 * @method Signing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SigningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Signing::class);
    }

    public function save(Signing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Signing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByEventIdAndUser(string $eventId, string $userId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.event = :event')
            ->andWhere('r.user = :user')
            ->setParameters([
                'event' => $eventId,
                'user' => $userId
            ])
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findWithoutCheckout($eventId, $userId): bool
    {
        $result = $this->createQueryBuilder('r')
            ->andWhere('r.event = :event')
            ->andWhere('r.user = :user')
            ->andWhere('r.checkin is not NULL and r.checkout is NULL')
            ->setParameters([
                'event' => $eventId,
                'user' => $userId
            ])
            ->getQuery()
            ->getOneOrNullResult();
    
        return $result !== null ? true : false;
    }
    

//    /**
//     * @return Signing[] Returns an array of Signing objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Signing
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
