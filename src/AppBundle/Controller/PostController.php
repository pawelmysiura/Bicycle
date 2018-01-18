<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\Type\ContactType;
use AppBundle\Form\Type\PostCommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    protected $limit = 3;

    /**
     * @Route("/panel/{page}",
     *     name="panel",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"})
     * @param $page
     * @return string
     */
    public function panelAction($page)
    {
        $pagination = $this->getPaginator([
            'status' => 'published',
            'orderBy' => 'p.publishDate',
            'order' => 'DESC'
        ], $page, $this->limit);

        return $this->render('panel/post/panel.html.twig', [
            'pagionator' => $pagination
        ]);
    }

    /**
     * @Route("/panel/post/{slug}",
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
     * @Route("/panel/postcategory/{slug}/{page}",
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
        $pagination = $this->getPaginator([
            'status' => 'published',
            'orderBy' => 'p.publishDate',
            'order' => 'DESC',
            'categorySlug' => $slug
        ], $page, $this->limit);

        return $this->render('panel/post/category.html.twig', [
            'pagionator' => $pagination
        ]);
    }

    /**
     * @Route("/panel/postctag/{slug}/{page}",
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
        $pagination = $this->getPaginator([
            'status' => 'published',
            'orderBy' => 'p.publishDate',
            'order' => 'DESC',
            'tagSlug' => $slug
        ], $page, $this->limit);

        return $this->render('panel/post/tag.html.twig', [
            'pagionator' => $pagination
        ]);
    }

    public function getPaginator(array $params = [], $page, $limit)
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $qb = $repository->getQueryBuilder($params);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
        return $pagination;
    }
}
