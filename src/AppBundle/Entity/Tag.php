<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.01.18
 * Time: 08:09
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Category
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 * @ORM\Table(name="bicycle_tags")
 */
class Tag extends AbstractEntity
{

    /**
     * @ORM\ManyToMany(
     *     targetEntity="AppBundle\Entity\Post",
     *     mappedBy="tag"
     * )
     */
    protected $posts;
}