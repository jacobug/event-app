<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {

            $timestamp = mt_rand(1658707200, 1678707200);
            $randomDate = date("d M Y H:00:00", $timestamp);
            
            $limits = ["none" => null, "small" => 100, "large" => 300];
            $eventLimit = array_rand($limits);

            $webinar = new Event();
            $webinar->setTitle('webinar_'.$i);
            $webinar->setEventDate(new \DateTimeImmutable($datetime = $randomDate));
            $webinar->setMaxLimit($limits[$eventLimit]);
            $webinar->setHostEmail('host@webinars.com');
            $manager->persist($webinar);
        }

        $manager->flush();

        for ($i = 0; $i < 50; $i++) {

            $events = $manager->getRepository(Event::class)->findAll();

            $contact = new Person();
            $contact->setEmail('user_'.$i.'@hotmail.com');
            $contact->addEvent($events[array_rand($events)]);
            $manager->persist($contact);
        }

        $manager->flush();
    }

}