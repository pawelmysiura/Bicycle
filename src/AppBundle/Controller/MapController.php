<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CommentMap;
use AppBundle\Entity\Map;
use AppBundle\Entity\MapImage;
use AppBundle\Form\Type\CreateMapType;
use AppBundle\Form\Type\MapCommentType;
use AppBundle\Form\Type\SearchContentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MapController extends BaseController
{

    /**
     * @Route("/map/create", name="panel_create_map")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createMapAction(Request $request)
    {
        $map = new Map(null);
        $form = $this->createForm(CreateMapType::class, $map);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            $map->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($map);

            /**
             * @var MapImage $image
             */
            foreach($map->getImage() as $image) {
                $image->setMap($map);
                $em->persist($image);
            }
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.map.map_create', [],'message'));
            return $this->redirectToRoute('panel_create_map');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', $this->get('translator')->trans('flashmsg.error.map.map_create', [],'message'));
        }
        return $this->render('panel/map/createMap.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/map/show/{id}", name="panel_show_map")
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param Map $map
     * @param Request $request
     * @param UploaderHelper $helper
     * @return Response
     */
    public function showMapAction(Map $map, Request $request, UploaderHelper $helper)
    {
        if ($map == null)
        {
            throw $this->createNotFoundException($this->get('translator')->trans('post_not_found', [],'exception'));
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

                $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.comment_send', [],'message'));
                return $this->redirectToRoute('panel_show_map', [
                    'id' => $map->getId()
                ]);
            }
        }
        $mapImageRepo = $this->getDoctrine()->getRepository(MapImage::class);
        $image = $mapImageRepo->findBy([
            'map' => $map->getId()
        ]);

        return $this->render('panel/map/showMap.html.twig', [
            'map' => $map,
            'form' => $form->createView(),
            'image' => $image
        ]);
    }

    /**
     * @Route("/map/edit/{id}", name="panel_map_edit")
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param Map $map
     * @param Request $request
     * @return Response
     */
    public function editMapAction(Map $map, Request $request)
    {
        if ($map->getAuthor() !== $this->getUser())
        {
            $this->addFlash('error', $this->get('translator')->trans('flashmsg.error.map.map_edit', [],'message'));
            return $this->redirectToRoute('panel');
        } else {
            $form = $this->createForm(CreateMapType::class, $map);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $em = $this->getDoctrine()->getManager();

                /**
                 * @var MapImage $image
                 */
                foreach ($map->getImage() as $image)
                {
                    $image->setMap($map);
                    $em->persist($image);
                }
                $em->persist($map);
                $em->flush();
                $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.map.map_edit', [],'message'));
                return $this->redirectToRoute('panel_show_map', [
                    'id' => $map->getId()
                ]);
            }

            return $this->render('panel/map/editMap.html.twig', [
                'form' => $form->createView(),
                'map' => $map
            ]);
        }

    }

    /**
     * @Route("/map/delete/{id}", name="panel_delete_map")
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param Map $map
     * @return Response
     */
    public function removeMapAction(Map $map)
    {
        if ($map->getAuthor() !== $this->getUser()) {
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.error.map.map_delete', [],'message'));
            return $this->redirectToRoute('panel');
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($map);
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.map.map_delete', [],'message'));
            return $this->redirectToRoute('panel_maps');
        }
    }

    /**
     * @Route("/maps/{page}", name="panel_maps", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return Response
     */
    public function allMapsAction($page)
    {
        $paginator = $this->getAllPagination($page, Map::class);
        return $this->render('panel/map/mapsList.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @Route("/add/favourite/{id}", name="panel_add_favourite_map")
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param Map $map
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addFavouriteAction(Map $map)
    {
        $map->addFavourite($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($map);
        $em->flush();

        $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.map.add_favourite', [],'message'));
        return $this->redirectToRoute('panel_show_map', [
            'id' => $map->getId()
        ]);
    }

    /**
     * @Route("/remove/favourite/{id}", name="panel_remove_favourite_map")
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param Map $map
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFavouriteAction(Map $map)
    {
        $map->removeFavorite($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($map);
        $em->flush();

        $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.map.remove_favourite', [],'message'));
        return $this->redirectToRoute('panel_show_map', [
            'id' => $map->getId()
        ]);
    }

    /**
     * @Route("/map/favourite/{page}", name="panel_favourite_maps", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return Response
     */
    public function favouriteListAction($page)
    {
        $reposiotry = $this->getDoctrine()->getRepository(Map::class);
        $qb = $reposiotry->getQueryBuilder([
            'userId' => $this->getUser()
        ]);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->limit);
        return $this->render('panel/map/favouriteList.html.twig', [
            'paginator' => $pagination
        ]);
    }

    /**
     * @Route("/user/maps/{page}", name="panel_user_maps", defaults={"page" = 1}, requirements={"page" = "\d+"})
     */
    public function userMapsAction($page) {
        $repo = $this->getDoctrine()->getRepository(Map::class);
        $qb = $repo->getQueryBuilder([
            'authorId' => $this->getUser()
        ]);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->limit);
        return $this->render('panel/map/userMapList.html.twig', [
            'paginator' => $pagination
        ]);

    }

    /**
     * @return Response
     */
    public function searchMapAction()
    {
        $form = $this->createForm(SearchContentType::class, null, [
            'method' => 'GET'
        ]);

        return $this->render('template/search.html.twig', [
            'form' => $form->createView(),
            'route' => 'panel_map_search'
        ]);
    }

    /**
     * @Route("/maps/search/{page}", name="panel_map_search", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param Request $request
     * @param $page
     * @return Response
     */
    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->query->get('search_content');

        if ($search == null)
        {
            return $this->redirectToRoute('panel_maps');
        } else {
            $pagination = $this->getQueryPagination([
                'searchMap' => $search['search']
            ], $page, Map::class);

            return $this->render('panel/map/mapsList.html.twig', [
                'paginator' => $pagination,
                'search' => $search['search']
            ]);
        }
    }

}
