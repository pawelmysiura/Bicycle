<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
            $this->addFlash('success', 'This user is now deactive!');
        } else {
            $user->setEnabled(1);
            $this->addFlash('success', 'This user is active!');
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('admin_users');
    }
}