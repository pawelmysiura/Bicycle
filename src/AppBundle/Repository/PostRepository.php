<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function getQueryBuilder(array $params = [])
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p, t, c, a')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tag', 't')
            ->leftJoin('p.author', 'a');

        if (!empty($params['status']))
        {
            if ('published' == $params['status'])
            {
                $qb->where('p.publishDate <= :currDate AND p.publishDate IS NOT NULL')
                    ->setParameter('currDate', new \DateTime());
            } elseif ('unpublished' == $params['status'])
            {
                $qb->where('p.publishDate > :currDate OR p.publishDate IS NULL')
                    ->setParameter('currDate', new \DateTime());
            }
        }
        if (!empty($params['orderBy']))
        {
            if (!empty($params['order']))
            {
                $order = $params['order'];
            } else {
                $order = null;
            }
            $qb->orderBy($params['orderBy'], $order);
        }
        return $qb;
    }
}