<?php

namespace AppBundle\Controller;


use AppBundle\Entity\EventSign;
use AppBundle\Form\Type\AvatarType;
use AppBundle\Form\Type\EditUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * @Route("/user/avatar", name="panel_user_avatar")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAvatarAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(AvatarType::class, $user);
        $submit = $this->submitForm($form, $user, $request, $this->get('translator')->trans('flashmsg.success.user_avatar', [], 'message'));
        if ($submit)
        {
            return $this->redirectToRoute('fos_user_profile_show');
        }
        return $this->render('panel/User/changeAvatar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/edit", name="panel_user_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUserAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);
        $submit = $this->submitForm($form, $user, $request, $this->get('translator')->trans('flashmsg.success.user_avatar', [], 'message'));
        if ($submit)
        {
            return $this->redirectToRoute('fos_user_profile_show');
        }
        return $this->render('panel/User/changeAvatar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/event/list/{page}", name="user_event_joined", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventJoinedAction($page)
    {
        $limit = 10;
        $user = $this->getUser();
        $eventSign = $this->getDoctrine()->getRepository(EventSign::class)->findBy([
            'user' => $user
        ]);
        $paginator = $this->get('knp_paginator');
        $paginate = $paginator->paginate($eventSign, $page, $limit);
        return $this->render('panel/User/joinedEvents.html.twig', [
            'paginator' => $paginate
        ]);

    }

}
