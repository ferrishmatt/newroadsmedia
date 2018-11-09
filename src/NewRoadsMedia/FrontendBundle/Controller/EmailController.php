<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/admin/emails")
 */
class EmailController extends Controller
{
    /**
     * @Route("/invoice/{invoiceTrackId}/")
     */
    public function invoiceAction($invoiceTrackId)
    {
        $invoiceTrack = $this->get('journalismjobs.manager.invoice_track')->find($invoiceTrackId);

        if ($this->shouldSendEmail()) {
            $this->get('journalismjobs.frontend.mailer')->sendInvoiceEmail($invoiceTrack);

            return new Response('<html><body>Email sent.</body></html>');
        }

        return $this->render('NewRoadsMediaFrontendBundle:Email:invoiceCreditCard.html.twig', array(
            'invoiceTrack' => $invoiceTrack,
        ));
    }

    /**
     * @Route("/invoice-attachment/{invoiceTrackId}/")
     * @Template()
     */
    public function invoiceAttachmentAction($invoiceTrackId)
    {
        $invoiceTrack = $this->get('journalismjobs.manager.invoice_track')->find($invoiceTrackId);
        $html = $this->renderView('NewRoadsMediaFrontendBundle:Email:invoiceAttachment.html.twig', array(
            'invoiceTrack' => $invoiceTrack,
        ));

        return new Response($this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
            'Content-Type'          => 'application/pdf',
           # 'Content-Disposition'   => 'attachment; filename="file.pdf"'
        ));
    }

    /**
     * @Route("/invoice-email/{invoiceTrackId}/")
     */
    public function invoiceEmailAction($invoiceTrackId)
    {
        $invoiceTrack = $this->get('journalismjobs.manager.invoice_track')->find($invoiceTrackId);

        return $this->render('NewRoadsMediaFrontendBundle:Email:invoiceEmail.html.twig', array(
            'invoiceTrack' => $invoiceTrack,
        ));
    }

    protected function shouldSendEmail()
    {
        return $this->getRequest()->get('send') == 1;
    }
}
