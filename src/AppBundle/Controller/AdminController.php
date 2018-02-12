<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\Map;
use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends BaseController
{
    /**
     * @Route("/", name="admin")
     */
    public function adminController()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/maps/{$page}", name="admin_maps", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mapListController($page)
    {
        $paginator = $this->getAllPagination($page, Map::class);
        return $this->render('admin/mapsList.html.twig', [
            'paginator' => $paginator
        ]);
    }
    /**
     * @Route("/posts/{page}", name="admin_posts", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postListController($page)
    {
        $paginator = $this->getQueryPagination([
            'status' => 'published',
            'order' => 'DESC'
        ], $page, Post::class);
        return $this->render('admin/postList.html.twig', [
            'paginator' => $paginator
        ]);
    }
    /**
     * @Route("/map/edit/{id}", name="admin_map_edit")
     */
    public function mapEditController()
    {

    }
    /**
     * @Route("/post/edit/{id}", name="admin_post_edit")
     */
    public function postEditController()
    {

    }
    /**
     * @Route("/post/new/{id}", name="admin_post_new")
     */
    public function postNewController()
    {

    }
    /**
     * @Route("/categories/{page}", name="admin_categories", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryListController($page)
    {
        $paginator = $this->getAllPagination($page, Category::class);
        return $this->render('admin/categoriesList.html.twig', [
            'paginator' => $paginator
        ]);
    }
    /**
     * @Route("/category/edit/{id}", name="admin_category_edit")
     */
    public function categoryEditCotroller()
    {

    }
    public function categoryNewController()
    {

    }
    /**
     * @Route("/tags/{page}", name="admin_tags", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tagListController($page)
    {
        $paginator = $this->getAllPagination($page, Tag::class);
        return $this->render('admin/tagsList.html.twig', [
            'paginator' => $paginator
        ]);
    }
    /**
     * @Route("/tag/edit/{id}", name="admin_tag_edit")
     */
    public function tagEditCotroller()
    {

    }
    public function tagNewController()
    {

    }
    /**
     * @Route("/users/{page}", name="admin_users", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersListController($page)
    {
        $paginator = $this->getAllPagination($page, User::class);
        return $this->render('admin/usersList.html.twig', [
            'paginator' => $paginator
        ]);
    }
}