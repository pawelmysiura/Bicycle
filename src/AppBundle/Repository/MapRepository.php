<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class MapRepository extends EntityRepository
{
    public function getQueryBuilder(array $params = [])
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('m, a, f')
            ->leftJoin('m.author', 'a')
            ->leftJoin('m.favourite', 'f');

        if (!empty($params['userId']))
        {
            $qb->andWhere('f.id = :userId')
                ->setParameter('userId', $params['userId']);
        }

        return $qb;
    }
}