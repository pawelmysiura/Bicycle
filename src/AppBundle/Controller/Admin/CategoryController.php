<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Form\Type\CreateCategoryType;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends BaseController
{
    /**
     * @Route("/categories/{page}", name="admin_categories", defaults={"page" = 1}, requirements={"page" = "\d+"})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryListAction($page)
    {
        $paginator = $this->getAllPagination($page, Category::class);
        return $this->render('admin/category/categoriesList.html.twig', [
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

        return $this->render('admin/category/editCategory.html.twig', [
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

        return $this->render('admin/category/editCategory.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/delete/{slug}", name="admin_category_delete")
     * @ParamConverter("category", class="AppBundle\Entity\Category", options={"mapping": {"slug": "slug"}})
     * @param $category
     * @return RedirectResponse
     */
    public function categoryDeleteAction($category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'Category deleted');
        return $this->redirectToRoute('admin_categories');
    }

}