<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CommentMap;
use AppBundle\Entity\Map;
use AppBundle\Form\Type\ContactType;
use AppBundle\Form\Type\CreateMapType;
use AppBundle\Form\Type\MapCommentType;
use AppBundle\Form\Type\PostCommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MapController extends Controller
{
    /**
     * @Route("panel/map/create", name="panel_create_map")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createMapAction(Request $request)
    {
        $map = new Map();
        $form = $this->createForm(CreateMapType::class, $map);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $em->persist($map);
            $em->flush();
            $this->addFlash('success', 'New path added');
            return $this->redirectToRoute('panel_create_map');
        }
        return $this->render('panel/map/createMap.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("panel/map/show/{id}", name="panel_show_map")
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param Map $map
     * @param Request $request
     * @return Response
     */
    public function showMapAction(Map $map, Request $request)
    {
        if ($map == null)
        {
            throw $this->createNotFoundException('map not found');
        }

        $comment = new CommentMap();
        $comment->setAuthor($this->getUser());
        $comment->setMap($map);
        $comment->setCreateDate(new \DateTime());
        $form = $this->createForm(MapCommentType::class, $comment);
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                $this->addFlash('success', 'Wiadomość wysłana');
                return $this->redirectToRoute('panel_show_map', [
                    'id' => $map->getId()
                ]);
            }
        }
        return $this->render('panel/map/showMap.html.twig', [
            'map' => $map,
            'form' => $form->createView()
        ]);
    }
}
