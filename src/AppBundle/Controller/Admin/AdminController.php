<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends BaseController
{

    /**
     * @Route("/", name="admin")
     */
    public function adminAction()
    {
        return $this->render('admin/index.html.twig');
    }

}