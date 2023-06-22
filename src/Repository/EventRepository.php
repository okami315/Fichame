<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Repository\TaskRepository;


/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findByPrecioSuperior($event)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Producto p
            WHERE p.precio > :precio'
        )->setParameter('event', $event);

        return $query->getResult();
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush)
        {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush)
        {
            $this->getEntityManager()->flush();
        }
    }

    public function createEventAlmacen(User $user, TaskRepository $taskRepository)
    {
        
        $event = new Event();
        $event->setName('Almacén');
        $event->setStartDate(new \DateTime());
        $event->setEndDate(new \DateTime());
        $event->setSchedule("");
        $event->setWorkersNumber(1);
        $event->setCompany($user->getCompany());
        $this->save($event, true);
        $task = $taskRepository->createAsignedTask($event, $user);
        $task->setType(1);
        $taskRepository->save($task,true);
        return $task->getId();
    }

  /**
 * @return Event[] Returns an array of Event objects
 */
public function findActiveEvents(): array
{
   $currentDate = new \DateTime();

   return $this->createQueryBuilder('e')
       ->andWhere('e.startDate < :currentDate')
       ->andWhere('e.endDate > :currentDate')
       ->andWhere('e.status = 1')
       ->setParameter('currentDate', $currentDate)
       ->getQuery()
       ->getResult();
}

//    /**
//     * @return Event[] Returns an array of Event objects
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

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}