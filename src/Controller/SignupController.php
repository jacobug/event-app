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
use Exception;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Serializer\Exception\UnsupportedException;

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
            $userEmail = $form->get('email')->getData();

            foreach ($event->getPersons() as $person){
                if($person->getEmail() === $userEmail){
                    throw new UnsupportedException("Person registered for this event", 400);    
                }
            }

            if ($event->getPersons()->count() === $event->getMaxLimit()){
                throw new UnsupportedException("Limit is reached", 400);
            }
            
            $attendee = new Person();
            $attendee->setEmail($userEmail);
            $attendee->addEvent($event);
           
            $manager->persist($attendee);
            $manager->flush();

            return $this->redirectToRoute('app_send_confirmation_user', [
                'eventId' => $event->getId(),
                'userId' => $attendee->getId(),
            ]);
        }
        
        $result = 'success';

        return $this->renderForm('signup/index.html.twig', [
            'form' => $form,
            'event' => $event,
            'result' => $result,
        ]);
    }
}
