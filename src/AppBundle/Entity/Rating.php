<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Rating
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RatingRepository")
 * @ORM\Table(name="bicycle_rating")
 */
class Rating
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    private $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Map",
     *     inversedBy="rating"
     * )
     * @ORM\JoinColumn(
     *     name="map_id",
     *     referencedColumnName="id"
     * )
     */
    protected $map;

        /**
         * @ORM\ManyToOne(
         *     targetEntity="AppBundle\Entity\User",
         *     inversedBy="rating"
         * )
         * @ORM\JoinColumn(
         *     name="user_id",
         *     referencedColumnName="id"
         * )
         */
    protected $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param mixed $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }


}