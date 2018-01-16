<?php
namespace AppBundle\Controller;

use AppBundle\Form\Type\ContactType;
use AppBundle\Service\Mailer\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller
{
    /**
     * @Route("/", name="front_index")
     */
    public function indexAction()
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/about", name="front_about")
     */
    public function aboutAction()
    {
        return $this->render('front/about.html.twig');
    }

    /**
     * @Route("/contact", name="front_contact")
     * @param Request $request
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request, Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($request->isMethod('POST')){

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $mailBody = $this->renderView('mailer/contact.html.twig', [
                'email' => $data['email'],
                'subject' => $data['subject'],
                'message' => $data['message']
            ]);
            $mailer->sendContactMail($mailBody);
            $this->get('session')->getFlashBag()->add('success', 'Message was send');
            return $this->redirectToRoute('front_contact');
        }}

        return $this->render('front/contact.html.twig', [
            'form' => $form->createView()
        ]);

    }
}