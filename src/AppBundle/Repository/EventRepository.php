<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function getQueryBuilder(array $params = [])
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e, a')
            ->leftJoin('e.author', 'a');

        if (!empty($params['searchEvent']))
        {
            $searchParam = '%'.$params['searchEvent'].'%';
            $qb->andWhere('e.title LIKE :searchEvent')
                ->setParameter('searchEvent', $searchParam);
        }
        return $qb;
    }
}