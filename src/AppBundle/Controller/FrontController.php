<?php
namespace AppBundle\Controller;

use AppBundle\Form\Type\ContactType;
use AppBundle\Service\Mailer\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller
{
    /**
     * @Route("/", name="front_index")
     */
    public function indexAction()
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('panel');
        } else {
            return $this->render('front/index.html.twig');
        }

    }

    /**
     * @Route("/about", name="front_about")
     */
    public function aboutAction()
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('panel');
        } else {
            return $this->render('front/about.html.twig');
        }
    }

    /**
     * @Route("/contact", name="front_contact")
     * @param Request $request
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request, Mailer $mailer)
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('panel');
        } else {
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
                    $this->addFlash('success', $this->get('translator')->trans('flashmsg.success.front.message_send', [], 'message'));
                    return $this->redirectToRoute('front_contact');
                }}

            return $this->render('front/contact.html.twig', [
                'form' => $form->createView()
            ]);
        }

    }
}