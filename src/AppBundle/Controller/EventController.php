<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CommentEvent;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventSign;
use AppBundle\Form\Type\CreateEventType;
use AppBundle\Form\Type\EventCommentType;
use AppBundle\Form\Type\SearchContentType;
use AppBundle\Service\Event\EventService;
use AppBundle\Service\Event\FormService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends BaseController
{
    /**
     * @Route("/create", name="event_create")
     * @param Request $request
     * @param FormService $formService
     * @return Response
     */
    public function createEventAction(Request $request, FormService $formService)
    {
        $user = $this->getUser();
        if (!$user->getFirstName() && !$user->getSurname()) {
            $this->addFlash('error', $this->get('translator')->trans('flashmsg.error.event.your_name', [], 'message'));
            return $this->redirectToRoute('panel_user_edit');
        }
        $event = new Event();
        $form = $this->createForm(CreateEventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $submitForm = $formService->submitCreateForm($user, $event);
            $this->addFlash($submitForm, $this->get('translator')->trans('flashmsg.'.$submitForm.'.event.create', [], 'message'));
            return $this->redirectToRoute('event_create');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', $this->get('translator')->trans('flashmsg.error.event.create', [], 'message'));
            return $this->redirectToRoute('event_create');
        }
        return $this->render('panel/event/createEvent.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("show/{slug}", name="show_event")
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @param Request $request
     * @return Response
     */
    public function showEventAction(Event $event, Request $request)
    {
        $user = $this->getUser();
        $comment = new CommentEvent();
        $comment->setAuthor($user);
        $comment->setEvent($event);
        $comment->setCreateDate(new \DateTime());
        $form = $this->createForm(EventCommentType::class, $comment);
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.comment_send', [],'message'));
                return $this->redirectToRoute('show_event', [
                    'slug' => $event->getSlug()
                ]);
            }
        }
        return $this->render('panel/event/showEvent.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{page}", name="event_list", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return Response
     */
    public function eventListAction($page)
    {
        $paginator = $this->getAllPagination($page, Event::class);
        return $this->render('panel/event/eventList.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @Route("edit/{slug}", name="event_edit")
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response|NotFoundHttpException
     */
    public function editEventAction(Event $event, Request $request)
    {
        if ($event->getAuthor() !== $this->getUser())
        {
            return new NotFoundHttpException($this->get('translator')->trans('event.access_denied', [], 'exception'));
        } else {
            $form = $this->createForm(CreateEventType::class, $event);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $event->setCreateDate(new \DateTime('now'));
                $event->setAuthor($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();
                $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.event.edit', [], 'message'));
                return $this->redirectToRoute('show_event', [
                    'slug' => $event->getSlug()
                ]);
            }elseif ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('error', $this->get('translator')->trans('flashmsg.error.event.edit', [],'message'));
                return $this->redirectToRoute('event_edit', [
                    'slug' => $event->getSlug()
                ]);
            }
        }
        return $this->render('panel/event/editEvent.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{slug}/apply", name="apply_event")
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @param EventService $eventService
     * @param int $permission
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function applyEventAction(Event $event, EventService $eventService, $permission = 0)
    {
        $user = $this->getUser();
        $join = $eventService->applyEvent($user, $event, $permission, 0);
        if ($join == false) {
            $this->addFlash('error', $this->get('translator')->trans('flashmsg.error.event.your_name', [], 'message'));
            return $this->redirectToRoute('show_event', [
                'slug' => $event->getSlug()
            ]);
        } elseif ($join == true) {
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.event.join', [], 'message'));
            return $this->redirectToRoute('show_event', [
                'slug' => $event->getSlug()
            ]);
        }
    }

    /**
     * @Route("/your/{page}", name="your_events", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return Response
     */
    public function yourEventsAction($page)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->findBy([
            'author' => $this->getUser()
        ]);
        $paginator = $this->get('knp_paginator');
        $paginate = $paginator->paginate($event, $page, $this->limit);
        return $this->render('panel/event/yourEvents.html.twig', [
            'paginator' => $paginate
        ]);
    }

    /**
     * @Route("/delete/{slug}", name="delete_event")
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @return Response
     */
    public function deleteEventAction(Event $event)
    {
        if ($event->getAuthor() !== $this->getUser()) {
            throw new NotFoundHttpException($this->get('translator')->trans('event.delete', [], 'exception'));
        } else {
            $eventSign = $this->getDoctrine()->getRepository(EventSign::class)->findBy([
                'event' => $event
            ]);
            $em = $this->getDoctrine()->getManager();
            foreach ($eventSign as $joined) {
                $em->remove($joined);
            }
            $em->remove($event);
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.event.delete', [], 'message'));
            return $this->redirectToRoute('event_list');
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchEventAction()
    {
        $form = $this->createForm(SearchContentType::class, null, [
            'method' => 'GET'
        ]);

        return $this->render('template/search.html.twig', [
            'form' => $form->createView(),
            'route' => 'event_search'
        ]);
    }

    /**
     * @Route("/search/{page}", name="event_search", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param Request $request
     * @param $page
     * @return Response
     */
    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->query->get('search_content');

        if ($search == null)
        {
            return $this->redirectToRoute('event_list');
        } else {
            $pagination = $this->getQueryPagination([
                'searchEvent' => $search['search']
            ], $page, Event::class);

            return $this->render('panel/event/eventList.html.twig', [
                'paginator' => $pagination,
                'search' => $search['search']
            ]);
        }
    }
}
