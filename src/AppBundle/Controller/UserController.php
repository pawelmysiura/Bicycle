<?php

namespace AppBundle\Controller;


use AppBundle\Form\Type\AvatarType;
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

}
