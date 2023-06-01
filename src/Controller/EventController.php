<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        $currentMonth = new \DateTime('first day of this month');
        $currentMonth->setTime(0, 0, 0);

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $events = $eventRepository->findAll();
        } else {
            $events = $eventRepository->findBy([
                'company' => $this->getUser()->getCompany()->getId(),
            ], ['startDate' => 'ASC']);
        }
        // Filtrar eventos del mes actual y eventos que comenzaron en meses anteriores pero finalizan en el mes actual
        $filteredEvents = [];
        foreach ($events as $evento) {
            $mesInicio = $evento->getStartDate()->format('F Y');
            $mesFin = $evento->getEndDate()->format('F Y');

            if ($mesInicio <= $currentMonth->format('F Y') && $mesFin == $currentMonth->format('F Y')) {
                // var_dump($mesInicio <= $currentMonth->format('F Y'));
                // var_dump($mesFin == $currentMonth->format('F Y'));
                // var_dump($mesInicio <= $currentMonth->format('F Y') && $mesFin == $currentMonth->format('F Y'));
                $filteredEvents[] = $evento;
            }
        }

        // Agrupa los eventos por meses
        $eventosPorMes = [];
        foreach ($events as $evento) {
            $mes = $evento->getStartDate()->format('F Y');
            $eventosPorMes[$mes][] = $evento;
        }

        return $this->render('event/index.html.twig', [
            'eventosPorMes' => $eventosPorMes,
        ]);
    }

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
            $eventRepository->save($event, true);

            foreach ($userRepository->findAll() as $user) {
                $taskRepository->createTask($event, $user);
            }

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
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

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
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
