<?php

namespace AppBundle\Service\Rating;

use AppBundle\Entity\Map;
use AppBundle\Entity\Rating;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ManagerRegistry as doctrine;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;
class RatingService
{
    /**
     * @var doctrine
     */
    private $doctrine;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Rating constructor.
     * @param doctrine $doctrine
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(doctrine $doctrine, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
        $this->translator = $translator;
    }

    public function addToSession($name, $requestId)
    {
        if ($requestId == null)
        {
            return $this->session->get($name);
        } else {
            $this->session->set($name, $requestId);
            return $this->session->get($name);
        }

    }


    public function existRating($id)
    {
        $ratingMap = $this->doctrine->getRepository(Rating::class)->findBy(['map' => $id]);
        $sum = 0;
        for ($i = 0 ; $i <= count($ratingMap)-1; $i++)
        {
            $sum+= $ratingMap[$i]->getRating();
        }
        if (count($ratingMap) == 0)
        {
            $average = 0;
        } else {
            $average = $sum / count($ratingMap);
        }
        return $average;
    }

    public function newRating($param, $user, $mapId)
    {
        $exist = $this->doctrine->getRepository(Rating::class)->findOneBy([
            'user' => $user->getId(),
            'map' => $mapId
        ]);
        $map = $this->doctrine->getRepository(Map::class)->findOneBy(['id' => $mapId]);

            $em = $this->doctrine->getManager();
            if ($exist === null) {
                $rating = new Rating();
                $rating->setUser($user);
                $rating->setMap($map);
                $rating->setRating($param);
                $em->persist($rating);
                $em->flush();
                return new JsonResponse(['success' => $this->translator->trans('rating_service.success.new',[] , 'services')]);
            } else {
                $exist->setRating($param);
                $em->persist($exist);
                $em->flush();
                return new JsonResponse(['success' => $this->translator->trans('rating_service.success.exist',[] , 'services')]);
            }
    }



}