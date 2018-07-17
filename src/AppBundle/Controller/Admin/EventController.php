<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventSign;
use AppBundle\Form\Type\SearchContentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends BaseController
{
    /**
     * @Route("event/{page}", name="events_admin", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return Response
     */
    public function eventsAction($page)
    {
        $paginator = $this->getAllPagination($page, Event::class);
        return $this->render('admin/event/events.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @Route("event/delete/{slug}", name="delete_event_admin")
     * @@ParamConverter("event", class="AppBundle\Entity\Event", options={"mapping": {"slug": "slug"}})
     * @param Event $event
     * @return Response
     */
    public function deleteEventAction(Event $event)
    {
        $eventSign = $this->getDoctrine()->getRepository(EventSign::class)->findBy([
            'event' => $event
        ]);
            $em = $this->getDoctrine()->getManager();
            foreach ($eventSign as $contestant) {
                $em->remove($contestant);
            }
            $em->remove($event);
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.event.delete', [], 'message'));
            return $this->redirectToRoute('events_admin');
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
            'route' => 'admin_event_search'
        ]);
    }

    /**
     * @Route("/event/search/{page}", name="admin_event_search", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param Request $request
     * @param $page
     * @return Response
     */
    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->query->get('search_content');

        if ($search == null)
        {
            return $this->redirectToRoute('events_admin');
        } else {
            $pagination = $this->getQueryPagination([
                'searchEvent' => $search['search']
            ], $page, Event::class);

            return $this->render('admin/event/events.html.twig', [
                'paginator' => $pagination,
                'search' => $search['search']
            ]);
        }
    }
}