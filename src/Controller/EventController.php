<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\TaskRepository;
use App\Repository\UserEventRepository;
use App\Repository\UserRepository;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository, UserEventRepository $userEventRepository): Response
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $events = $eventRepository->findAll();
        } else {
            $events = $eventRepository->findBy([
                'company' => $this->getUser()->getCompany()->getId(),
            ], ['startDate' => 'ASC']);
        }

        $currentMonth = new \DateTime();
        $currentMonth->modify('first day of this month');

        $events = $eventRepository->createQueryBuilder('e')
            ->where('e.endDate >= :currentMonth')
            ->setParameter('currentMonth', $currentMonth)
            // ->orderBy('e.startDate', 'ASC')
            ->getQuery()
            ->getResult();

            // Separar eventos por meses y años
        $eventosPorMes = [];
        $eventosPorMesTrabajadores = [];
        foreach ($events as $event) {
            $startDate = $event->getStartDate();
            $endDate = $event->getEndDate();

            $monthYear = $endDate->format('F Y');

            $firstDayOfMonth = clone $endDate;
            $firstDayOfMonth->modify('first day of this month');

            if ($startDate < $firstDayOfMonth) {
                $monthYear = $endDate->format('F Y');
            } else {
                $monthYear = $startDate->format('F Y');
            }

            if (!isset($eventosPorMes[$monthYear])) {
                $eventosPorMes[$monthYear] = [];
            }

            $eventosPorMes[$monthYear][] = $event;

            if ($event->getStatus() === 1) {
                if (!isset($eventosPorMesTrabajadores[$monthYear])) {
                    $eventosPorMesTrabajadores[$monthYear] = [];
                }

                $eventosPorMesTrabajadores[$monthYear][] = $event;
            }

            $countUsers = $userEventRepository->countUsersWithAvailabilityByEventId($event->getId());
            $event->setWorkersAvailable($countUsers);
            $eventRepository->save($event, true);

            // Hacer condicion que si se cumple cambiar el status a 2 para que se vea verde el icono
            if($event->getStatus()!=0 and $event->getWorkersSelected()==$event->getWorkersNumber() and $event->getDriversNumber()==$event->getDriversAvailable() and $eventRepository->hasCoordination($event->getId())){
                $event->setStatus(2);
                $eventRepository->save($event, true);
            }else if($event->getStatus()==0){
                $event->setStatus(0);
                $eventRepository->save($event, true);
            }else {
                $event->setStatus(1);
                $eventRepository->save($event, true);
            }
        }

        return $this->render('event/index.html.twig', [
            'eventosPorMes' => $eventosPorMes,
            'eventosPorMesTrabajadores' => $eventosPorMesTrabajadores,
        ]);
    }

    // #[IsGranted('ROLE_ADMIN')]
    // #[Route('/event/{id}/workers', name: 'app_event_show_workers', methods: ['GET'])]
    // public function showWorkers(Event $event): Response
    // {
    //     SecurityController::checkCompany($this, $this->getUser()->getCompany()->getNif(), $event->getCompany()->getNif());
    //     return $this->render('event/trabajadores.html.twig', [
    //         'event' => $event,
    //     ]);
    // }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/event/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        SecurityController::checkCompany($this, $this->getUser()->getCompany()->getNif(), $event->getCompany()->getNif());
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/event/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository, UserRepository $userRepository, TaskRepository $taskRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $event->setCompany($this->getUser()->getCompany());
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setCreateDate(new DateTime());
            $event->setDriversAvailable(0);
            $event->setWorkersSelected(0);
            $event->setPendingWorkers(0);
            $eventRepository->save($event, true);

            // foreach ($userRepository->findAll() as $user) {
            //     $taskRepository->createTask($event, $user);
            // }

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/event/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        SecurityController::checkCompany($this, $this->getUser()->getCompany()->getNif(), $event->getCompany()->getNif());

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        // Hacer condicion que si se cumple cambiar el status a 2 para que se vea verde el icono
        if($event->getStatus()!=0 and $event->getWorkersSelected()==$event->getWorkersNumber() and $event->getDriversNumber()==$event->getDriversAvailable() and $eventRepository->hasCoordination($event->getId())){
            $event->setStatus(2);
            $eventRepository->save($event, true);
        }else{
            $event->setStatus(1);
            $eventRepository->save($event, true);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setEditDate(new DateTime());
            $eventRepository->save($event, true);

            return $this->render('event/edit.html.twig', [
                'event' => $event,
                'form' => $form,
            ]);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/event/close/{id}', name: 'app_event_close_id', methods: ['POST'])]
    public function close(Request $request, Event $event, EventRepository $eventRepository): Response
    {

        $event->setStatus(2);
        $eventRepository->save($event, true);
        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/event/open/{id}', name: 'app_event_open_id', methods: ['POST'])]
    public function open(Request $request, Event $event, EventRepository $eventRepository , UserRepository $userRepository ,UserEventRepository $userEventRepository): Response
    {
        $event->setStatus(1);
        // $event->setPendingWorkers($userRepository->countUsersWithNullDisponibility());
      

        // Se asignan todos los user_events para después sacar el número de ellos que quedan por marcar disponibilidad
        foreach ($userRepository->findWithoutRole() as $user) {
                $userEventRepository->createUserEvent($event, $user);
        }
        $event->setPendingWorkers($userEventRepository->countUsersWithoutDisponibilityByEventId($event->getId()));
        $eventRepository->save($event, true);
        
        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/event/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        SecurityController::checkCompany($this, $this->getUser()->getCompany()->getNif(), $event->getCompany()->getNif());

        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/event/{id}/report', name: 'app_event_report', methods: ["GET"])]
    public function report(Event $event, TaskRepository $taskRepository, EventRepository $eventRepository, $id): Response
    {
        SecurityController::checkCompany($this, $this->getUser()->getCompany()->getNif(), $event->getCompany()->getNif());

        $tasks = $event->getTasks();
        $arrayTasks = $taskRepository->findBy(
            ["Event" => $id],
            ["state" => "ASC"]
        );

        return $this->render('admin/event.html.twig', [
            'tasks' => $arrayTasks,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/newAlmacen', name: 'app_event_newAlmacen', methods: ['GET', 'POST'])]
    public function newAlmacen(Request $request, EventRepository $eventRepository, TaskRepository $taskRepository): Response
    {

        $taskId = $eventRepository->createEventAlmacen($this->getUser(), $taskRepository);


        return $this->redirectToRoute('app_task_update_State', ['id' => $taskId], Response::HTTP_SEE_OTHER);
    }
}
