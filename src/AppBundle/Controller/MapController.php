<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Map;
use AppBundle\Entity\Post;
use AppBundle\Form\Type\ContactType;
use AppBundle\Form\Type\CreateMapType;
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
            return $this->redirectToRoute('panel_create_map');
            }
        return $this->render('panel/map/createMap.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
