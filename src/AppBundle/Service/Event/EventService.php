<?php

namespace AppBundle\Service\Event;

use AppBundle\Entity\EventSign;
use Doctrine\Common\Persistence\ManagerRegistry as doctrine;
use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

class EventService
{
    /**
     * @var doctrine
     */
    private $doctrine;

    /**
     * @var TCPDFController
     */
    private $tcpdf;

    /**
     * EventService constructor.
     * @param $doctrine
     * @param $tcpdf
     */
    public function __construct($doctrine, $tcpdf)
    {
        $this->doctrine = $doctrine;
        $this->tcpdf = $tcpdf;
    }


    public function codeGenerator()
    {
        $random = random_bytes(30);
        return base64_encode(bin2hex($random));
    }

    public function applyEvent($user, $event, $permission, $verify)
    {
        $this->isUserAlreadySign($user, $event);
            if (!$user->getFirstName() && !$user->getSurname()) {
                return false;
            } else {
                $eventSign = new EventSign();
                $eventSign->setEvent($event);
                $eventSign->setUser($user);
                $eventSign->setCode($this->codeGenerator());
                $eventSign->setVerify($verify);
                $eventSign->setPermissions($permission);
                $eventSign->setJoinDate(new \DateTime('now'));
                $em = $this->doctrine->getManager();
                $em->persist($eventSign);
                $em->flush();
                return true;
            }
    }

    public function changeNumber(array $data, $event, $user)
    {
        $signRepo = $this->doctrine->getRepository(EventSign::class);
        $eventSign = $signRepo->findOneBy([
            'user' => $user,
            'event' => $event
        ]);

        $em = $this->doctrine->getManager();
        if ($data['changeNumber'] == 1) {
            $changeNumber = $signRepo->findOneBy([
                'startNumber' => $data['startNumber'],
                'event' => $event
            ]);
            if ($changeNumber !== null) {
                $oldNumber = $eventSign->getStartNumber();
                $changeNumber->setStartNumber($oldNumber);
                $em->persist($changeNumber);
            }
        }
        $eventSign->setStartNumber($data['startNumber']);
        $em->persist($eventSign);
        $em->flush();
    }

    public function returnPDFResponseFromHTML($html, $event)
    {
        $pdf = $this->tcpdf->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setAuthor('Bicycle Portal');
        $pdf->setTitle($event->getTitle() . ' Application');
        $pdf->setSubject($event->getTitle() . ' Application');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 11, '', true);
        $pdf->AddPage();
        $filename = $event->getTitle() . '_Application';
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename . ".pdf", 'I');
    }

    public function submitCreateForm($form, $user, $event, $request)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setCreateDate(new \DateTime('now'));
            $event->setAuthor($user);
            $em = $this->doctrine->getManager();
            $em->persist($event);
            $em->flush();
            $this->applyEvent($user, $event, 2, 1);
            return 'success';
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return 'error';
        }
    }

    public function isUserAlreadySign($user, $event)
    {
        $repo = $this->doctrine->getRepository(EventSign::class);
        $eventSign = $repo->findOneBy([
            'event' => $event,
            'user' => $user
        ]);
        if ($eventSign == null)
        {
            return false;
        } else {
            throw new EnvNotFoundException('Już dołączyłeś');
        }
    }
}