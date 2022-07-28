<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ListAtendeesController extends AbstractController
{
    #[Route('/list/attendees/{id}', name: 'app_list_attendees')]
    public function findAllAttendees(ManagerRegistry $manager, int $id): Response
    {
        $event = $manager->getRepository(Event::class)->find($id);

        $attendees = $event->getPersons();
        foreach ($attendees as $Person){
            $emailsCollection[] = [
                'email' => $Person->getEmail()
            ];
        }
        
        return new JsonResponse($emailsCollection);
    }
}
