<?php

namespace App\Controller;

use App\Entity\Signing;
use App\Entity\Event;
use App\Form\SigningType;
use App\Repository\EventRepository;
use App\Repository\SigningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/signing')]
class SigningController extends AbstractController
{
    #[Route('/{id}', name: 'app_signing_index', methods: ['GET'])]
    public function index(Event $event, EventRepository $eventRepository, SigningRepository $signingRepository): Response
    {
        $signings = $signingRepository->findByEventIdAndUser($event->getId(), $this->getUser()->getId());
        $bool = $signingRepository->findWithoutCheckout($event->getId(), $this->getUser()->getId());
        
        return $this->render('signing/index.html.twig', [
            'signings' => $signings,
            'event' => $event,
            'bool' => $bool,
        ]);
    }

    #[Route('/new/{id}', name: 'app_signing_new', methods: ['GET', 'POST'])]
    public function new($id, Request $request, EventRepository $eventRepository, SigningRepository $signingRepository, Security $security): Response
    {
        $evento = $eventRepository->find($id);
        $user = $security->getUser();

        $signing = new Signing();

        $signing->setEvent($evento);
        $signing->setUser($user);
        $signing->setCheckin(null);
        $signing->setCheckout(null);
        $signing->setCreatecheckin(null);
        $signing->setCreatecheckout(null);
        
        $signingRepository->save($signing, true);

        return $this->redirectToRoute('app_signing_show', ['id' => $signing->getId()], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/{id}', name: 'app_signing_show', methods: ['GET'])]
    // public function show(Signing $signing, EventRepository $eventRepository): Response
    // {
    //     $event = $eventRepository->find($signing->getEvent());
    //     return $this->render('signing/show.html.twig', [
    //         'signing' => $signing,
    //         'event' => $event,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_signing_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Signing $signing, SigningRepository $signingRepository): Response
    {
        $form = $this->createForm(SigningType::class, $signing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $signingRepository->save($signing, true);

            return $this->redirectToRoute('app_signing_index', ['id' => $signing->getEvent()->getId()], [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('signing/edit.html.twig', [
            'signing' => $signing,
            'form' => $form,
            'event' => $signing->getEvent(),
        ]);
    }

    #[Route('/{id}', name: 'app_signing_delete', methods: ['POST'])]
    public function delete(Request $request, Signing $signing, SigningRepository $signingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$signing->getId(), $request->request->get('_token'))) {
            $signingRepository->remove($signing, true);
        }

        return $this->redirectToRoute('app_signing_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/entrada/{id}', name: 'app_signing_entrada', methods: ['GET', 'POST'])]
    public function entrada($id,Request $request,EventRepository $eventRepository, SigningRepository $signingRepository): Response
    {
        $evento = $eventRepository->find($id);
        $signing = new Signing();
        $signing->setEvent($evento);
        $signing->setUser($this->getUser());
        $signing->setCheckin(new \DateTime);
        $signing->setCreateCheckin(new \DateTime);
        $signing->setCheckout(null);
        $signing->setCreatecheckout(null);
     
        $signingRepository->save($signing, true);

        return $this->redirectToRoute('app_signing_index', ['id' => $evento->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/salida', name: 'app_signing_salida', methods: ['GET', 'POST'])]
    public function salida(Request $request, Signing $signing, SigningRepository $signingRepository): Response
    {
        $signing->setCheckout(new \DateTime);
        $signing->setCreatecheckout(new \DateTime);
        $signingRepository->save($signing, true);

        return $this->redirectToRoute('app_signing_index', ['id' => $signing->getEvent()->getId()], Response::HTTP_SEE_OTHER);
    }
}
