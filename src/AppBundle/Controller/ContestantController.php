<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventSign;
use AppBundle\Entity\User;
use AppBundle\Form\Type\ChangeNumberType;
use AppBundle\Form\Type\ChangePermissionsType;
use AppBundle\Service\Event\EventService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContestantController extends BaseController
{

    /**
     * @Route("/your/contestant/{slug}/{page}", name="contestant_list", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @param $page
     * @return Response
     */
    public function contestantsAction(Event $event, $page)
    {
        $eventSignRepo = $this->getDoctrine()->getRepository(EventSign::class);
        $eventSign = $eventSignRepo->findOneBy([
            'event' => $event->getId(),
            'user' => $this->getUser()
        ]);
        if ($this->getUser() == $event->getAuthor() || $eventSign->getPermissions() == 2) {
            $limit = 10;
            $contestantList = $eventSignRepo->findBy([
                'event' => $event->getId()
            ]);
            $paginator = $this->get('knp_paginator');
            $paginate = $paginator->paginate($contestantList, $page, $limit);
            return $this->render('panel/event/contestant/contestantList.html.twig', [
                'paginator' => $paginate,
                'event' => $event
            ]);
        } else {
            throw new NotFoundHttpException($this->get('translator')->trans('event.access_denied', [], 'exception'));
        }
    }

    /**
     * @Route("accept/{slug}/{code}", name="event_accept_contestant")
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @param $code
     * @return Response
     */
    public function acceptContestantAction(Event $event, $code)
    {
        $user = $this->getUser();
        $repository = $eventSign = $this->getDoctrine()->getRepository(EventSign::class);
        $eventSign = $repository->findOneBy([
            'user' => $user,
            'event' => $event
        ]);
        if ($eventSign->getPermissions() == 0)
        {
            throw new NotFoundHttpException($this->get('translator')->trans('event.access_denied', [], 'exception'));
        } else {
            $findContestant = $repository->findOneBy([
                'code' => $code,
                'event' => $event
            ]);
            $findContestant->setVerify(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($findContestant);
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.contestant.verify', [], 'message'));
            return $this->render('panel/event/contestant/contestant.html.twig', [
                'contestant' => $findContestant
            ]);
        }
    }

    /**
     * @Route("setNumber/{slug}", name="event_set_numbers")
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setNumbersAction(Event $event)
    {
        $signRepo = $this->getDoctrine()->getRepository(EventSign::class);
        $eventSign = $signRepo->getQueryBuilder([
            'event' => $event,
            'orderBy' => 's.joinDate',
            'order' => 'ASC'
        ]);
        $em = $this->getDoctrine()->getManager();
        for ($i = 1; $i <= count($eventSign); $i++)
        {
                $eventSign[$i-1]->setStartNumber($i);
            $em->persist($eventSign[$i-1]);
        }
        $em->flush();
        $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.event.set_numbers', [], 'message'));
        return $this->redirectToRoute('contestant_list', [
            'slug' => $event->getSlug()
        ]);
    }

    /**
     * @Route("changeNumber/{slug}/{username}", name="event_change_number")
     * @ParamConverter("user", class="AppBundle\Entity\User", options={"mapping": {"username": "username"}})
     * @param User $user
     * @param $slug
     * @param Request $request
     * @param EventService $eventService
     * @return Response
     */
    public function changeNumberAction(User $user, $slug, Request $request, EventService $eventService)
    {
        $eventRepo = $this->getDoctrine()->getRepository(Event::class);
        $event = $eventRepo->findOneBy([
            'slug' => $slug
        ]);
        if ($event->getAuthor() !== $this->getUser()) {
            throw new NotFoundHttpException($this->get('translator')->trans('event.access_denied', [], 'exception'));
        }
        $form = $this->createForm(ChangeNumberType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $eventService->changeNumber($data, $event, $user);
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.event.change_number', [], 'message'));
            return $this->redirectToRoute('contestant_list', [
                'slug' => $event->getSlug()
            ]);
        }
        return $this->render('panel/event/contestant/changeNumberContestant.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/application/{slug}", name="event_application")
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @return Response
     */
    public function EventApplicationAction(Event $event)
    {
        $user = $this->getUser();
        $eventSignRepo = $this->getDoctrine()->getRepository(EventSign::class);
        $eventSign = $eventSignRepo->findOneBy([
            'event' => $event,
            'user' => $user
        ]);

        return $this->render('panel/event/contestant/application.html.twig', [
            'eventSign' => $eventSign
        ]);
    }

    /**
     * @Route("/application/pdf/{slug}", name="event_pdf_application")
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @param EventService $eventService
     * @return void
     */
    public function eventPDFApplicationAction(Event $event, EventService $eventService)
    {
        $user = $this->getUser();
        $eventSignRepo = $this->getDoctrine()->getRepository(EventSign::class);
        $eventSign = $eventSignRepo->findOneBy([
            'event' => $event,
            'user' => $user
        ]);

        $html = $this->renderView('panel/event/contestant/PDFApplication.html.twig', [
            'eventSign' => $eventSign
        ]);
        $eventService->returnPDFResponseFromHTML($html, $event);
    }

    /**
     * @Route("/permissions/{slug}/{username}", name="event_permissions")
     * @ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @param $username
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function setPermissionsAction(Event $event, $username, Request $request)
    {
        $eventSignRepo = $this->getDoctrine()->getRepository(EventSign::class);
        $userPermissions = $eventSignRepo->findOneBy([
            'event' => $event,
            'user' => $this->getUser()
        ]);
        $contestantRepo = $this->getDoctrine()->getRepository(User::class);
        $contestant = $contestantRepo->findOneBy([
            'username' => $username
        ]);
        $eventSign = $eventSignRepo->findOneBy([
            'user' => $contestant,
            'event' => $event
        ]);
        $form = $this->createForm(ChangePermissionsType::class, $eventSign);
        if ($this->getUser() == $event->getAuthor() ) {

            $formSubmitted = $this->submitForm($form, $eventSign, $request, $this->get('translator')->trans('flashmsg.success.contestant.change_permissions', [], 'message'));
            if ($formSubmitted == true) {
                return $this->redirectToRoute('contestant_list', [
                'slug' => $event->getSlug()
                ]);
            }

        } elseif ($userPermissions->getUser() == $this->getUser() && $userPermissions->getPermissions() > 1) {

            $formSubmitted = $this->submitForm($form, $eventSign, $request, $this->get('translator')->trans('flashmsg.success.contestant.change_permissions', [], 'message'));
            if ($formSubmitted == true) {
                return $this->redirectToRoute('contestant_list', [
                    'slug' => $event->getSlug()
                ]);
            }

        } else {
            throw new NotFoundHttpException($this->get('translator')->trans('event.access_denied', [], 'exception'));
        }
        return $this->render('panel/event/contestant/changePermissionsContestant.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
