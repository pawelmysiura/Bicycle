<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected $limit = 5;

    public function getQueryPagination(array $params = [], $page, $class)
    {
        $repository = $this->getDoctrine()->getRepository($class);
        $qb = $repository->getQueryBuilder($params);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->limit);
        return $pagination;
    }

    public function getAllPagination($page, $class)
    {
        $reposiotry = $this->getDoctrine()->getRepository($class);
        $qb = $reposiotry->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->limit);
        return $pagination;
    }

}