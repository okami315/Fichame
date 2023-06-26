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
        
        if ($data['disponibility'] == "null") {
 
            $evento->setPendingWorkers($evento->getPendingWorkers()+1);
            $eventRepository->save($evento, true);
           
            $userEvent->setDisponibility(null);
            $userEventRepository->save($userEvent, true);
        } else {
            $anterior = $userEvent->getDisponibility();
            if($anterior === null){
                $evento->setPendingWorkers($evento->getPendingWorkers()-1);
                $eventRepository->save($evento, true);
            }
            $userEvent->setDisponibility($data['disponibility']);
            $userEventRepository->save($userEvent, true);
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);

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
    public function updateAsistance(Request $request, int $id, UserEventRepository $userEventRepository,  EventRepository $eventRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $userEvent = $userEventRepository->find($id);
        $event = $userEvent->getEvent();

        if ($data['asistance'] == "null") {

            // Si la asistencia antes era true ahora se resta
            if($userEvent->getAsistance()==true){
                // Si estaba marcado como conductor se debe restar
                if($userEvent->isDriving()){
                    $event->setDriversAvailable($event->getDriversAvailable()-1);
                    $eventRepository->save($event, true);
                }
            $event->setWorkersSelected($event->getWorkersSelected()-1);
            $eventRepository->save($event, true);
            }
         
            $userEvent->setAsistance(null);
            $userEventRepository->save($userEvent, true);
           
        } else if ($data['asistance'] == true) {
            // Comprobamos que el driving no esté true por haberlo marcado antes
            if($userEvent->isDriving()){
                $event->setDriversAvailable($event->getDriversAvailable()+1);
                $eventRepository->save($event, true);  
            }
            $userEvent->setAsistance($data['asistance']);
            $userEventRepository->save($userEvent, true);
            $event->setWorkersSelected($event->getWorkersSelected()+1);
            $eventRepository->save($event, true);
        }else{

            // Si la asistencia antes era true ahora se resta
            if($userEvent->getAsistance()==true){
                // Si estaba marcado como conductor se debe restar 
                if($userEvent->isDriving()){
                    $event->setDriversAvailable($event->getDriversAvailable()-1);
                    $eventRepository->save($event, true);
                }
                $event->setWorkersSelected($event->getWorkersSelected()-1);
                $eventRepository->save($event, true);
            }
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
        if($coordinador){
            $userEvent->setRealHours(($userEvent->getRealHours())+2);
            $userEvent->setEstimatedHours(($userEvent->getEstimatedHours())+2);
        }else{
            $userEvent->setRealHours(($userEvent->getRealHours())-2);
            $userEvent->setEstimatedHours(($userEvent->getEstimatedHours())-2);
        }
        $userEvent->setRealsalary($userEvent->getRealHours()*10);
        $userEvent->setEstimatedsalary($userEvent->getEstimatedHours()*10);
        $userEventRepository->save($userEvent, true);

        return new Response('Coordinador actualizado correctamente', Response::HTTP_OK);
    }

    #[Route('/update-driving/{id}', name: 'app_user_event_updateDriving', methods: ['GET', 'POST'])]
    public function updateDriving(Request $request, int $id, UserEventRepository $userEventRepository, EventRepository $eventRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $userEvent = $userEventRepository->find($id);
        $event = $userEvent->getEvent();

        $driving = $data['driving'] == '1' ? true : false;
        if($driving){
            // Sumar 1 a drivers_available
            $event->setDriversAvailable($event->getDriversAvailable()+1);
            $eventRepository->save($event, true);

            $userEvent->setRealHours(($userEvent->getRealHours())+4);
            $userEvent->setEstimatedHours(($userEvent->getEstimatedHours())+4);
        }else{
            // Restar 1 a drivers_available
            $event->setDriversAvailable($event->getDriversAvailable()-1);
            $eventRepository->save($event, true);

            $userEvent->setRealHours(($userEvent->getRealHours())-4);
            $userEvent->setEstimatedHours(($userEvent->getEstimatedHours())-4);
        }
        $userEvent->setRealsalary($userEvent->getRealHours()*10);
        $userEvent->setEstimatedsalary($userEvent->getEstimatedHours()*10);
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
        $userEvent->setRealHours($userEvent->getEstimatedHours()+$userEvent->getExtraHours());

        $userEvent->setEstimatedsalary($userEvent->getEstimatedHours()*10);
        $userEvent->setRealsalary($userEvent->getRealHours()*10);
        // $userEvent->setRealsalary($userEvent->getEstimatedsalary()+($userEvent->getExtraHours())*10);
        
        $userEventRepository->save($userEvent, true);

        return new Response('Horario estimado actualizado correctamente', Response::HTTP_OK);
    }

    #[Route('/update-extra-hours/{id}', name: 'app_user_event_updateExtraHours', methods: ['GET', 'POST'])]
    public function updateExtraHours(Request $request, int $id, UserEventRepository $userEventRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $userEvent = $userEventRepository->find($id);

        $userEvent->setExtraHours($data['extraHours']);

    
        $userEvent->setRealHours($userEvent->getEstimatedHours()+$userEvent->getExtraHours());

        $userEvent->setRealsalary($userEvent->getEstimatedsalary()+($userEvent->getExtraHours())*10);
        

        $userEventRepository->save($userEvent, true);

        return new Response('Horas extra actualizadas correctamente', Response::HTTP_OK);
    }
}
