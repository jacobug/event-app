<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendConfirmationUserController extends AbstractController
{
    #[Route('/send/confirmation/user', name: 'app_send_confirmation_user')]
    public function index(MailerInterface $mailer, Request $request, EntityManagerInterface $manager): Response
    {
        $event = $manager->getRepository(Event::class)->findOneBy([
            'id' => $request->query->get('eventId'),
        ]);
        $attendee = $manager->getRepository(Person::class)->findOneBy([
            'id' => $request->query->get('userId'),
        ]);
        

        $confirmation = (new Email())
            ->from($event->getHostEmail())
            ->to($attendee->getEmail())
            ->subject('Welcome on the Webinar')
            ->bcc($event->getHostEmail())
            ->text('Thank you for registering to "'. $event->getTitle() .'" 
                event that will be held on '. $event->getEventDate()->format("d-m-Y H:i:s"))
            ->html('<p>Thank you for registering to "'. $event->getTitle() .'" 
                event that will be held on '. $event->getEventDate()->format("d-m-Y H:i:s") .'</p>');

            try {
                $mailer->send($confirmation);
            } catch (TransportExceptionInterface $e) {
                echo $e;
            }

        return $this->render('send_confirmation_user/index.html.twig', [
            
        ]);
    }
}
