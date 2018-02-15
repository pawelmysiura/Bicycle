<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostCommentType;
use AppBundle\Form\Type\SearchContentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends BaseController
{

    /**
     * @Route("/{page}",
     *     name="panel",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"})
     * @param $page
     * @return string
     */
    public function panelAction($page)
    {
        $pagination = $this->getQueryPagination([
            'status' => 'published',
            'orderBy' => 'p.publishDate',
            'order' => 'DESC'
        ], $page, Post::class);

        return $this->render('panel/post/panel.html.twig', [
            'pagionator' => $pagination
        ]);
    }

    /**
     * @Route("/post/{slug}",
     *     name="panel_post"
     * )
     * @ParamConverter("post", class="AppBundle\Entity\Post", options={"mapping": {"slug": "slug"}})
     * @param Post $post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Post $post, Request $request)
    {
        if ($post === null)
        {
            throw $this->createNotFoundException('Artykułu nie znaleziono');
        }
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setPost($post);
        $comment->setCreateDate(new \DateTime());
        $form = $this->createForm(PostCommentType::class, $comment);
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                $this->addFlash('success', 'Wiadomość wysłana');
                return $this->redirectToRoute('panel_post', [
                    'slug' => $post->getSlug()
                ]);
            }
        }
        return $this->render('panel/post/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/postcategory/{slug}/{page}",
     *     name="panel_post_category",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $slug
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($slug, $page)
    {
        $pagination = $this->getQueryPagination([
            'status' => 'published',
            'orderBy' => 'p.publishDate',
            'order' => 'DESC',
            'categorySlug' => $slug
        ], $page, Post::class);

        return $this->render('panel/post/category.html.twig', [
            'pagionator' => $pagination
        ]);
    }

    /**
     * @Route("/postctag/{slug}/{page}",
     *     name="panel_post_tag",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param $slug
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tagAction($slug, $page)
    {
        $pagination = $this->getQueryPagination([
            'status' => 'published',
            'orderBy' => 'p.publishDate',
            'order' => 'DESC',
            'tagSlug' => $slug
        ], $page, Post::class);

        return $this->render('panel/post/tag.html.twig', [
            'pagionator' => $pagination
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchPostAction()
    {
        $form = $this->createForm(SearchContentType::class);
        return $this->render('template/search.html.twig', [
            'form' => $form->createView(),
            'route' => 'panel_post_search'
        ]);
    }

    /**
     * @Route("/posts/search/{page}", name="panel_post_search", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->request->get('search_content');

        $pagination = $this->getQueryPagination([
            'status' => 'published',
            'orderBy' => 'p.publishDate',
            'order' => 'DESC',
            'searchPost' => $search['search']
        ], $page, Post::class);
        return $this->render('panel/post/panel.html.twig', [
            'pagionator' => $pagination,
            'search' => $search['search']
        ]);
    }

//    public function getPaginator(array $params = [], $page, $limit)
//    {
//        $repository = $this->getDoctrine()->getRepository(Post::class);
//        $qb = $repository->getQueryBuilder($params);
//        $paginator = $this->get('knp_paginator');
//        $pagination = $paginator->paginate($qb, $page, $limit);
//        return $pagination;
//    }
}
