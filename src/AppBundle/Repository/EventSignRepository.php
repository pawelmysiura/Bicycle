<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class EventSignRepository extends EntityRepository
{
    public function getQueryBuilder(array $params = [])
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->leftJoin('s.event', 'e');
        $qb->leftJoin('s.user', 'u');

        if (!empty($params['event'])) {
            $qb->andWhere('s.event = :eventId')
                ->setParameter('eventId', $params['event']);
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
        return $qb->getQuery()->getResult();
    }
}