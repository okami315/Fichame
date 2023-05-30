<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\UserEvent;
use App\Form\DisponibilidadType;
use App\Repository\EventRepository;
use App\Repository\UserEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/user/event')]
class UserEventController extends AbstractController
{

    #[Route('/disponibilidad/{id}', name: 'app_user_event_disponibilidad', methods: ['GET', 'POST'])]
    public function disponibilidad(Security $security, Request $request, int $id, EventRepository $eventRepository ,UserEventRepository $userEventRepository): Response
    {
        $evento = $eventRepository->find($id);
        $usuario = $security->getUser();

        $userEvent = $userEventRepository->findOneBy([
            'users' => $usuario,
            'events' => $evento,
        ]);

        if (!$userEvent) {
            $userEvent = new UserEvent();
            $userEvent->setUsers($usuario);
            $userEvent->setEvents($evento);
        }

        $form = $this->createForm(DisponibilidadType::class, $userEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEventRepository->save($userEvent, true);

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_event/new.html.twig', [
            'user_event' => $userEvent,
            'form' => $form,
        ]);
    }

    #[Route('/', name: 'app_user_event_index', methods: ['GET'])]
    public function index(UserEventRepository $userEventRepository): Response
    {
        return $this->render('user_event/index.html.twig', [
            'user_events' => $userEventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserEventRepository $userEventRepository): Response
    {
        $userEvent = new UserEvent();
        $form = $this->createForm(UserEventType::class, $userEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEventRepository->save($userEvent, true);

            return $this->redirectToRoute('app_user_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_event/new.html.twig', [
            'user_event' => $userEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_event_show', methods: ['GET'])]
    public function show(UserEvent $userEvent): Response
    {
        return $this->render('user_event/show.html.twig', [
            'user_event' => $userEvent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserEvent $userEvent, UserEventRepository $userEventRepository): Response
    {
        $form = $this->createForm(UserEventType::class, $userEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEventRepository->save($userEvent, true);

            return $this->redirectToRoute('app_user_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_event/edit.html.twig', [
            'user_event' => $userEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_event_delete', methods: ['POST'])]
    public function delete(Request $request, UserEvent $userEvent, UserEventRepository $userEventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userEvent->getId(), $request->request->get('_token'))) {
            $userEventRepository->remove($userEvent, true);
        }

        return $this->redirectToRoute('app_user_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
