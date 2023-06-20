<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\UserEvent;
use App\Form\DisponibilidadType;
use App\Form\AsistanceType;
use App\Form\UserEventType;
use App\Repository\EventRepository;
use App\Repository\UserEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/user/event')]
class UserEventController extends AbstractController
{

    #[Route('/disponibility/{id}', name: 'app_user_event_disponibility', methods: ['GET', 'POST'])]
    public function disponibility(Security $security, Request $request, int $id, EventRepository $eventRepository, UserEventRepository $userEventRepository): Response
    {
        $evento = $eventRepository->find($id);
        $usuario = $security->getUser();
        $data = json_decode($request->getContent(), true);


        $userEvent = $userEventRepository->findOneBy([
            'user' => $usuario,
            'event' => $evento,
        ]);

        if (!$userEvent) {
            $userEvent = new UserEvent();
            $userEvent->setUser($usuario);
            $userEvent->setEvent($evento);
        }

        // $form = $this->createForm(AsistanceType::class, $userEvent);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

        if ($data['disponibility'] == "null") {
            $userEvent->setDisponibility(null);
            $userEventRepository->save($userEvent, true);
        } else {
            $userEvent->setDisponibility($data['disponibility']);
            $userEventRepository->save($userEvent, true);
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->render('user_event/newAsistance.html.twig', [
        //     'user_event' => $userEvent,
        //     'form' => $form,
        // ]);
    }

    #[Route('/', name: 'app_user_event_index', methods: ['GET'])]
    public function index(UserEventRepository $userEventRepository, UserRepository $userRepository): Response
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
        if ($this->isCsrfTokenValid('delete' . $userEvent->getId(), $request->request->get('_token'))) {
            $userEventRepository->remove($userEvent, true);
        }

        return $this->redirectToRoute('app_user_event_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/update-asistance/{id}', name: 'app_user_event_updateAsistance', methods: ['GET', 'POST'])]
    public function updateAsistance(Request $request, int $id, UserEventRepository $userEventRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $userEvent = $userEventRepository->find($id);

        if ($data['asistance'] == "null") {
            $userEvent->setAsistance(null);
            $userEventRepository->save($userEvent, true);
        } else {
            $userEvent->setAsistance($data['asistance']);
            $userEventRepository->save($userEvent, true);
        }

        return $this->json(['message' => 'Asistencia actualizada correctamente']);
    }

    #[Route('/update-coordinador/{id}', name: 'app_user_event_updateCoordinador', methods: ['GET', 'POST'])]
    public function updateCoordinador(Request $request, int $id, UserEventRepository $userEventRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $userEvent = $userEventRepository->find($id);

        $coordinador = $data['coordinador'] == '1' ? true : false; 

        $userEvent->setCoordination($coordinador);
        $userEventRepository->save($userEvent, true);

        return new Response('Coordinador actualizado correctamente', Response::HTTP_OK);
    }

    #[Route('/update-driving/{id}', name: 'app_user_event_updateDriving', methods: ['GET', 'POST'])]
    public function updateDriving(Request $request, int $id, UserEventRepository $userEventRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $userEvent = $userEventRepository->find($id);

        $driving = $data['driving'] == '1' ? true : false; 

        $userEvent->setDriving($driving);
        $userEventRepository->save($userEvent, true);

        return new Response('Conductor actualizado correctamente', Response::HTTP_OK);
    }

    #[Route('/update-privateCar/{id}', name: 'app_user_event_updatePrivateCar', methods: ['GET', 'POST'])]
    public function updatePrivateCar(Request $request, int $id, UserEventRepository $userEventRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $userEvent = $userEventRepository->find($id);

        $privateCar = $data['privateCar'] == '1' ? true : false; 

        $userEvent->setPrivateCar($privateCar);
        $userEventRepository->save($userEvent, true);

        return new Response('Coche particular actualizado correctamente', Response::HTTP_OK);
    }

    #[Route('/update-estimated-hours/{id}', name: 'app_user_event_updateEstimatedHours', methods: ['GET', 'POST'])]
    public function updateEstimatedHours(Request $request, int $id, UserEventRepository $userEventRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $userEvent = $userEventRepository->find($id);

        $userEvent->setEstimatedHours($data['estimatedHours']);
        $userEventRepository->save($userEvent, true);

        return new Response('Horario estimado actualizado correctamente', Response::HTTP_OK);
    }

    #[Route('/update-extra-hours/{id}', name: 'app_user_event_updateExtraHours', methods: ['GET', 'POST'])]
    public function updateExtraHours(Request $request, int $id, UserEventRepository $userEventRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $userEvent = $userEventRepository->find($id);

        $userEvent->setExtraHours($data['extraHours']);
        $userEventRepository->save($userEvent, true);

        return new Response('Horas extra actualizadas correctamente', Response::HTTP_OK);
    }
}
