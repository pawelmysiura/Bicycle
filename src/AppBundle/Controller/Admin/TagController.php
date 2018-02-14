<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Tag;
use AppBundle\Form\Type\CreateTagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TagController extends BaseController
{
    /**
     * @Route("/tags/{page}", name="admin_tags", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tagListAction($page)
    {
        $paginator = $this->getAllPagination($page, Tag::class);
        return $this->render('admin/tag/tagsList.html.twig', [
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

        return $this->render('admin/tag/editTag.html.twig', [
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

        return $this->render('admin/tag/editTag.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag
        ]);
    }

    /**
     * @Route("/tag/delete/{slug}", name="admin_tag_delete")
     * @ParamConverter("tag", class="AppBundle\Entity\Tag", options={"mapping": {"slug": "slug"}})
     * @param $tag
     * @return RedirectResponse
     */
    public function tagDeleteAction($tag)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($tag);
        $em->flush();
        $this->addFlash('success', 'Tag deleted');
        return $this->redirectToRoute('admin_tags');
    }
}