<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Post;
use AppBundle\Form\Type\CreatePostType;
use AppBundle\Form\Type\SearchContentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends BaseController
{
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
        return $this->render('admin/post/postList.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @Route("/post/edit/{slug}", name="admin_post_edit")
     * @ParamConverter("post", class="AppBundle\Entity\Post", options={"mapping": {"slug": "slug"}})
     * @param $post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postEditAction(Post $post, Request $request)
    {
        if (!$post)
        {
            throw $this->createNotFoundException('Post not found');
        }
        $form = $this->createForm(CreatePostType::class, $post);

        $form->handleRequest($request);
        $image = $form->get('file')->getData();
                if ( $image !== null) {
                    $post->setPath(null);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($post);
                    $em->flush();
                }
        if ($form->isSubmitted() & $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.admin.post_edit', [], 'message'));
            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('admin/post/editPost.html.twig', [
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
        $post->setCreateDate(new \DateTime('now'));
        $form = $this->createForm(CreatePostType::class, $post);
        $submit = $this->submitForm($form, $post, $request, $this->get('translator')->trans('flashmsg.success.admin.post_created', [], 'message'));
        if ($submit)
        {
            return $this->redirectToRoute('admin_posts');
        }
        return $this->render('admin/post/editPost.html.twig', [
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
        $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.admin.post_delete', [], 'message'));
        return $this->redirectToRoute('admin_posts');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchPostAction()
    {
        $form = $this->createForm(SearchContentType::class, null , [
            'method' => 'GET'
        ]);
        return $this->render('template/search.html.twig', [
            'form' => $form->createView(),
            'route' => 'admin_post_search'
        ]);
    }

    /**
     * @Route("/posts/search/{page}", name="admin_post_search", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->query->get('search_content');

        if ($search == null) {
            return $this->redirectToRoute('admin_maps');
        } else {
            $pagination = $this->getQueryPagination([
                'order' => 'DESC',
                'searchPost' => $search['search']
            ], $page, Post::class);
            return $this->render('admin/post/postList.html.twig', [
                'paginator' => $pagination,
                'search' => $search['search']
            ]);
        }
    }
}