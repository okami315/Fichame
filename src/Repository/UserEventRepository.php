<?php

namespace App\Repository;

use App\Entity\UserEvent;
use App\Entity\User;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserEvent>
 *
 * @method UserEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserEvent[]    findAll()
 * @method UserEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEvent::class);
    }

    public function save(UserEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getUsersDisponibilities()
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.disponibility = :disponibility')
            ->setParameter('disponibility', 1)
            ->getQuery();

        return $query->getResult();
    }

    public function createUserEvent(Event $event, User $user): void
    {
        $userEvent = new UserEvent();
        $userEvent->setUser($user);
        $userEvent->setEvent($event);
        $userEvent->setRealHours($event->getEstimatedHours());
        $userEvent->setEstimatedHours($event->getEstimatedHours());
        $userEvent->setRealsalary($event->getEstimatedHours()*10);
        $userEvent->setEstimatedsalary($event->getEstimatedHours()*10);
        $this->save($userEvent, true);
    }
    public function getUserForIdEvent($id)
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.event_id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getResult();
    }

    public function countUsersWithAvailabilityByEventId($eventId): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery('
        SELECT COUNT(ue.id)
        FROM App\Entity\UserEvent ue
        WHERE ue.event = :eventId
        AND ue.disponibility = 1
    ')
            ->setParameter('eventId', $eventId);

        return $query->getSingleScalarResult();
    }

    public function countUsersWithoutDisponibilityByEventId($eventId): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery('
        SELECT COUNT(ue.id)
        FROM App\Entity\UserEvent ue
        WHERE ue.event = :eventId
        AND ue.disponibility IS NULL
    ')
            ->setParameter('eventId', $eventId);

        return $query->getSingleScalarResult();
    }



    //    /**
    //     * @return UserEvent[] Returns an array of UserEvent objects
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

    //    public function findOneBySomeField($value): ?UserEvent
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
