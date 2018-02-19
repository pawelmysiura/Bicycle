<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.01.18
 * Time: 06:33
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Post
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @ORM\Table(name="bicycle_post")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Post
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max="128"
     * )
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"}, separator="-", unique=true, updatable=false)
     * @ORM\Column(type="string", length=120, unique=true)
     *
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Category",
     *     inversedBy="posts"
     * )
     * @ORM\JoinColumn(
     *     name="category_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     */
    private $category;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Tag",
     *     inversedBy="posts"
     * )
     * @ORM\JoinTable(
     *     name="bicycle_post_tags"
     * )
     */
    private $tag;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\User"
     * )
     * @ORM\JoinColumn(
     *     name="author_id",
     *     referencedColumnName="id"
     * )
     */
    private $author;


    /**
     * @ORM\Column(type="datetime")
     */
    private $createDeate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $publishDate;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Comment",
     *     mappedBy="post"
     * )
     * @ORM\OrderBy({"createDate" = "DESC"})
     */
    private $comment;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * Add Tag
     * @param mixed $tag
     * @return $this
     */
    public function addTag(Tag $tag){
        $this->tag[] = $tag;
        return $this;
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
    public function getCreateDeate()
    {
        return $this->createDeate;
    }

    /**
     * @param mixed $createDeate
     */
    public function setCreateDeate($createDeate)
    {
        $this->createDeate = $createDeate;
    }

    /**
     * @return mixed
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * @param mixed $publishDate
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
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