<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Map
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MapRepository")
 * @ORM\Table(name="bicycle_map")
 * @ORM\HasLifecycleCallbacks()
 */
class Map
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $start;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $end;

    /**
     * @ORM\Column(type="string")
     */
    private $waypoints;

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
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getWaypoints()
    {
        return $this->waypoints;
    }

    /**
     * @param mixed $waypoints
     */
    public function setWaypoints($waypoints)
    {
        $this->waypoints = $waypoints;
    }


}