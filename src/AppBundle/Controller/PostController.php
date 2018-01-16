<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    protected $limit = 3;

    /**
     * @Route("/panel/{page}",
     *     name="panel",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"})
     * @param $page
     * @return string
     */
    public function panelAction($page)
    {
        $pagination = $this->getPaginator([
            'status' => 'published',
            'orderBy' => 'p.publishDate',
            'order' => 'DESC'
        ], $page, $this->limit);

        return $this->render('panel/post/panel.html.twig', [
            'pagionator' => $pagination
        ]);
    }

    public function getPaginator(array $params = [], $page, $limit)
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $qb = $repository->getQueryBuilder($params);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
        return $pagination;
    }
}
