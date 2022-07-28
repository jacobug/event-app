<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $manager): Response
    {
        $availableEvents = $manager->getRepository(Event::class)->findFutureEvents();

        return $this->render('index/index.html.twig', [
            'events' => $availableEvents,
        ]);
    }
}
