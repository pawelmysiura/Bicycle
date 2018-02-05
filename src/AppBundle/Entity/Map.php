<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max="150"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text", length=300)
     * @Assert\Length(
     *     max="300"
     * )
     */
    private $description;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\User"
     * )
     * @ORM\JoinColumn(
     *     name="author_id",
     *     referencedColumnName="id",
     *     nullable=false
     * )
     */
    private $author;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\User",
     *     inversedBy="favouriteMap"
     * )
     * @ORM\JoinTable(
     *     name="bicycle_favourite_map"
     * )
     */
    private $favourite;

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
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\CommentMap",
     *     mappedBy="map"
     * )
     * @ORM\OrderBy({"createDate" = "DESC"})
     */
    private $comment;

    /**
     * Map constructor.
     * @param $favourite
     */
    public function __construct($favourite)
    {
        $this->favourite = new ArrayCollection();
    }


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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getFavourite()
    {
        return $this->favourite;
    }

    /**
     * @param mixed $favourite
     */
    public function setFavourite($favourite)
    {
        $this->favourite = $favourite;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addFavourite(User $user)
    {
        $this->favourite[] = $user;
        return $this;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function removeFavorite($userId)
    {
        return $this->favourite->removeElement($userId);
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

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }



}