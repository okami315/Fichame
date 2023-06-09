<?php

namespace App\Controller;
use App\Entity\Company;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use App\Repository\UserEventRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register/{nif}', name: 'app_register', methods: ['POST','GET'])]
    public function register(Request $request, UserEventRepository $userEventRepository,EventRepository $eventRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, Company $company): Response
    {
        $user = new User();
        $user->setCompany($company);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setRegDate( new DateTime('now'));
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            
            );
            
            $user->setRegDate(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            
        
            $events  = $eventRepository->findActiveEvents();
            
            foreach ($events as $event) {
                $userEventRepository->createUserEvent($event, $user);
                $event->setPendingWorkers($event->getPendingWorkers()+1);
            }   
            // Asignar user_event para todos los eventos disponibles recorrerlos con un foreach
            // $userEventRepository->createUserEvent($event, $user);
        
            // Adicionalmente para ese evento aumentar en uno al actual el valor de los pending



            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
