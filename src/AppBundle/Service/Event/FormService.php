<?php

namespace AppBundle\Service\Event;

use Doctrine\Common\Persistence\ManagerRegistry as doctrine;

class FormService
{
    /**
     * @var doctrine
     */
    private $doctrine;

    /**
     * @var EventService
     */
    private $eventService;

    /**
     * EventService constructor.
     * @param $doctrine
     * @param $eventService
     */

    public function __construct($doctrine, $eventService)
    {
        $this->doctrine = $doctrine;
        $this->eventService = $eventService;
    }

    public function submitCreateForm($user, $event)
    {
            $event->setCreateDate(\DateTime::createFromFormat('U', time()));
            $event->setAuthor($user);
            $em = $this->doctrine->getManager();
            $em->persist($event);
            $em->flush();
            $this->eventService->applyEvent($user, $event, 2, 1);
            return 'success';
    }


}