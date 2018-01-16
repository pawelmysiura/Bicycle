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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @ORM\Table(name="bicycle_category")
 */
class Category extends AbstractEntity
{

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Post",
     *     mappedBy="category"
     * )
     */
    protected $posts;
}