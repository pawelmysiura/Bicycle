<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\Map;
use AppBundle\Entity\MapImage;
use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use AppBundle\Form\Type\CreateCategoryType;
use AppBundle\Form\Type\CreateMapType;
use AppBundle\Form\Type\CreatePostType;
use AppBundle\Form\Type\CreateTagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends BaseController
{
    /**
     * @Route("/", name="admin")
     */
    public function adminAction()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/maps/{$page}", name="admin_maps", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mapListAction($page)
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
    public function postListAction($page)
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
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param $map
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function mapEditAction($map, Request $request)
    {
        $form = $this->createForm(CreateMapType::class, $map);
        $form->handleRequest($request);
        if ($form->isSubmitted() & $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            /**
             * @var MapImage $image
             */
            foreach ($map->getImage as $image)
            {
                $image->setMap($map);
                $em->persist($image);
            }
            $em->persist($map);
            $em->flush();
            $this->addFlash('success', 'Done!');
            return $this->redirectToRoute('admin_maps');
        }
        return $this->render('admin/editMap.html.twig', [
            'form' => $form->createView(),
            'map' => $map
        ]);
    }

    /**
     * @Route("/map/delete/{id}", name="admin_map_delete")
     * @ParamConverter("map", class="AppBundle\Entity\Map", options={"mapping": {"id": "id"}})
     * @param $map
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteMapAction($map)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($map);
        $em->flush();
        $this->addFlash('success', 'You have deleted map');
        return $this->redirectToRoute('admin_maps');
    }

    /**
     * @Route("/post/edit/{slug}", name="admin_post_edit")
     * @ParamConverter("post", class="AppBundle\Entity\Post", options={"mapping": {"slug": "slug"}})
     * @param $post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postEditAction($post, Request $request)
    {
        if (!$post)
        {
            throw $this->createNotFoundException('Post not found');
        }
        $form = $this->createForm(CreatePostType::class, $post);
        $submit = $this->submitForm($form, $post, $request, 'Post has been edited');
        if ($submit)
        {
            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('admin/editPost.html.twig', [
           'form' => $form->createView(),
            'post' => $post
        ]);
    }

    /**
     * @Route("/post/create", name="admin_post_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postCreateAction(Request $request)
    {
        $post = new Post();
        $post->setAuthor($this->getUser());
        $post->setCreateDeate(new \DateTime());
        $form = $this->createForm(CreatePostType::class, $post);
        $submit = $this->submitForm($form, $post, $request, 'Post has been created');
        if ($submit)
        {
            return $this->redirectToRoute('admin_posts');
        }
        return $this->render('admin/editPost.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/delete/{slug}", name="admin_post_delete")
     * @ParamConverter("post", class="AppBundle\Entity\Post", options={"mapping": {"slug": "slug"}})
     * @param $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postDeleteAction($post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('admin_posts');
    }
    /**
     * @Route("/categories/{page}", name="admin_categories", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryListAction($page)
    {
        $paginator = $this->getAllPagination($page, Category::class);
        return $this->render('admin/categoriesList.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @Route("/category/edit/{slug}", name="admin_category_edit")
     * @ParamConverter("category", class="AppBundle\Entity\Category", options={"mapping": {"slug": "slug"}})
     * @param $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryEditAction($category, Request $request)
    {
        if (!$category)
        {
            throw $this->createNotFoundException('Category not found');
        }
        $form = $this->createForm(CreateCategoryType::class, $category);
        $submit = $this->submitForm($form, $category, $request, 'Category has been changed');
        if ($submit)
        {
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/editCategory.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/create", name="admin_category_create")
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function categoryCreateAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CreateCategoryType::class, $category);
        $submit = $this->submitForm($form, $category, $request, 'Category has been created');
        if ($submit)
        {
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/editCategory.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }
    /**
     * @Route("/tags/{page}", name="admin_tags", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tagListAction($page)
    {
        $paginator = $this->getAllPagination($page, Tag::class);
        return $this->render('admin/tagsList.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @Route("/tag/edit/{slug}", name="admin_tag_edit")
     * @ParamConverter("tag", class="AppBundle\Entity\Tag", options={"mapping": {"slug": "slug"}})
     * @param $tag
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function tagEditAction($tag, Request $request)
    {
        $form = $this->createForm(CreateTagType::class, $tag);
        $submit = $this->submitForm($form, $tag, $request, 'Tag has been edited');
        if ($submit)
        {
            return $this->redirectToRoute('admin_tags');
        }

        return $this->render('admin/editTag.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag
        ]);
    }

    /**
     * @Route("/tag/create", name="admin_tag_create")
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function tagCreateAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(CreateTagType::class, $tag);
        $submit = $this->submitForm($form, $tag, $request, 'Tag has been created');
        if ($submit)
        {
            return $this->redirectToRoute('admin_tags');
        }

        return $this->render('admin/editTag.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag
        ]);
    }
    /**
     * @Route("/users/{page}", name="admin_users", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersListAction($page)
    {
        $paginator = $this->getAllPagination($page, User::class);
        return $this->render('admin/usersList.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @param $form
     * @param $param
     * @param $request
     * @param $message
     * @return bool
     */
    public function submitForm($form, $param, $request, $message)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() & $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($param);
            $em->flush();
            $this->addFlash('success', $message);
            return true;
        }
    }
}