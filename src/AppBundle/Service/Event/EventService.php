<?php

namespace AppBundle\Service\Event;

use AppBundle\Entity\EventSign;
use Doctrine\Common\Persistence\ManagerRegistry as doctrine;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @var Generator
     */
    private $generator;


    /**
     * EventService constructor.
     * @param $doctrine
     * @param $tcpdf
     * @param $generator
     */

    public function __construct($doctrine, $tcpdf, $generator )
    {
        $this->doctrine = $doctrine;
        $this->tcpdf = $tcpdf;
        $this->generator = $generator;
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
                $eventSign->setCode($this->generator->codeGenerator());
                $eventSign->setVerify($verify);
                $eventSign->setPermissions($permission);
//                $eventSign->setJoinDate(new \DateTime('now'));
                $eventSign->setJoinDate(\DateTime::createFromFormat('U', time()));
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
        return $eventSign;
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
        return $pdf;
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
            throw new NotFoundHttpException('Już dołączyłeś');
        }
    }

}