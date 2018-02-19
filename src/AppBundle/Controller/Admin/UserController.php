<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\User;
use AppBundle\Form\Type\SearchContentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * @Route("/users/{page}", name="admin_users", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersListAction($page)
    {
        $paginator = $this->getAllPagination($page, User::class);
        return $this->render('admin/user/usersList.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @Route("/user/active/{id}", name="admin_user_active")
     * @ParamConverter("user", class="AppBundle\Entity\User", options={"mapping": {"id": "id"}})
     * @param User $user
     * @return RedirectResponse
     */
    public function userActiveAction(User $user)
    {
        if ($user->isEnabled() == 1)
        {
            $user->setEnabled(0);
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.admin.user_deactive', [], 'message'));
        } else {
            $user->setEnabled(1);
            $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.admin.user_active', [], 'message'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchUserAction()
    {
        $form = $this->createForm(SearchContentType::class  );

        return $this->render('template/search.html.twig', [
            'form' => $form->createView(),
            'route' => 'admin_user_search'
        ]);
    }

    /**
     * @Route("/user/search/{page}", name="admin_user_search", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleSearchAction(Request $request, $page)
    {
        $search = $request->request->get('search_content');

//        $pagination = $this->getQueryPagination([
//            'searchUser' => $search['search']
//        ], $page, User::class);
        $reposiotry = $this->getDoctrine()->getRepository(User::class);
        $qb = $reposiotry->findBy([
            'username' => $search['search']
        ]);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->limit);

        return $this->render('admin/user/usersList.html.twig', [
            'paginator' => $pagination,
            'search' => $search['search']
        ]);
    }
}