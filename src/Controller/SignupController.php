<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SignupFormType;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class SignupController extends AbstractController
{
    #[Route('/signup/{id}', methods: ['GET'], name: 'app_signup')]
    public function index(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $event = $manager->getRepository(Event::class)->findOneBy([
            'id' => $id,
        ]);

        $person = new Person();

        $form = $this->createForm(SignupFormType::class, $person, [
            'eventId' => $id,
        ]);

        return $this->renderForm('signup/index.html.twig', [
            'form' => $form,
            'event' => $event,
            'eventId' => $id,
        ]);
    }

    #[Route('/signup/{id}', methods: ['POST'], name: 'app_signup_add')]
    public function add(Request $request, EntityManagerInterface $manager, int $id): Response
    {
        
        $event = $manager->getRepository(Event::class)->findOneBy([
            'id' => $id,
        ]);

        $person = new Person();

        $form = $this->createForm(SignupFormType::class, $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $session = new Session();
            
            $userEmail = $form->get('email')->getData();

            foreach ($event->getPersons() as $person){
                if($person->getEmail() === $userEmail){
                    $session->getFlashBag()->add(
                    'error',
                    'Provided email is already subscribed for this event.'
                );
                    return $this->renderForm('signup/index.html.twig', [
                        'form' => $form,
                        'event' => $event,
                        'eventId' => $id,
                    ]);   
                }
            }

            if ($event->getPersons()->count() === $event->getMaxLimit()){
                $session->getFlashBag()->add(
                    'error',
                    'The limit for this event has been reached.'
                );

                return $this->renderForm('signup/index.html.twig', [
                    'form' => $form,
                    'event' => $event,
                    'eventId' => $id,
                ]);
            }
            
            $attendee = new Person();
            $attendee->setEmail($userEmail);
            $attendee->addEvent($event);
           
            $manager->persist($attendee);
            $manager->flush();

            return $this->redirectToRoute('app_send_confirmation', [
                'eventId' => $event->getId(),
                'userId' => $attendee->getId(),
            ]);
        }

        return $this->renderForm('signup/index.html.twig', [
            'form' => $form,
            'event' => $event,
        ]);
    }
}
