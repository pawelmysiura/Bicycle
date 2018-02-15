<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Map;
use AppBundle\Entity\MapImage;
use AppBundle\Form\Type\CreateMapType;
use AppBundle\Form\Type\SearchContentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MapController extends BaseController
{

    /**
     * @Route("/maps/{$page}", name="admin_maps", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mapListAction($page)
    {
        $paginator = $this->getAllPagination($page, Map::class);
        return $this->render('admin/map/mapsList.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @Route("/map/edit/{id}", name="admin_map_edit")
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param $map
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function mapEditAction($map, Request $request)
    {
        $form = $this->createForm(CreateMapType::class, $map);
        $form->handleRequest($request);
        if ($form->isSubmitted() & $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            /**
             * @var MapImage $image
             */
            foreach ($map->getImage as $image)
            {
                $image->setMap($map);
                $em->persist($image);
            }
            $em->persist($map);
            $em->flush();
            $this->addFlash('success', 'Done!');
            return $this->redirectToRoute('admin_maps');
        }
        return $this->render('admin/map/editMap.html.twig', [
            'form' => $form->createView(),
            'map' => $map
        ]);
    }

    /**
     * @Route("/map/delete/{id}", name="admin_map_delete")
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param $map
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteMapAction($map)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($map);
        $em->flush();
        $this->addFlash('success', 'You have deleted map');
        return $this->redirectToRoute('admin_maps');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchMapAction()
    {
        $form = $this->createForm(SearchContentType::class  );

        return $this->render('template/search.html.twig', [
            'form' => $form->createView(),
            'route' => 'admin_map_search'
        ]);
    }

    /**
     * @Route("/maps/search/{page}", name="admin_map_search", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param Request $request
     * @param $page
     * @return Response
     */
    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->request->get('search_content');

        $pagination = $this->getQueryPagination([
            'searchMap' => $search['search']
        ], $page, Map::class);

        return $this->render('admin/map/mapsList.html.twig', [
            'paginator' => $pagination,
            'search' => $search['search']
        ]);
    }
}