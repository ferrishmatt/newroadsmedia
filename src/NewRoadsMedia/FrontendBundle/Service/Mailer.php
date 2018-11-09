<?php

namespace NewRoadsMedia\FrontendBundle\Service;

use NewRoadsMedia\FrontendBundle\Entity\Application;
use NewRoadsMedia\FrontendBundle\Entity\Employer;
use NewRoadsMedia\FrontendBundle\Entity\Invoice;
use NewRoadsMedia\FrontendBundle\Entity\InvoiceTrack;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Entity\Notification;
use NewRoadsMedia\FrontendBundle\Entity\Payment;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;
use Knp\Snappy\GeneratorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\ValidatorInterface;

class Mailer
{
    /** @var float */
    protected $adCost;

    /** @var float */
    protected $diversityAdCost;

    /** @var float */
    protected $featuredAdCost;

    /** @var GeneratorInterface */
    protected $knpSnappy;

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var EngineInterface */
    protected $templateEngine;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var string */
    protected $siteTitle;

    /** @var string */
    protected $contactEmail;

    /** @var string */
    protected $accountingEmail;

    /** @var string */
    protected $infoEmail;

    /** @var string */
    protected $receiptEmail;

    /** @var string */
    protected $logoPath;

    public function __construct($templateEngine, $mailer, $validator, $knpSnappy, $adCost, $diversityAdCost, $featuredAdCost, $siteTitle, $contactEmail, $accountingEmail, $infoEmail, $receiptEmail, $logoPath)
    {
        $this->templateEngine = $templateEngine;
        $this->mailer = $mailer;
        $this->validator = $validator;
        $this->knpSnappy = $knpSnappy;
        $this->adCost = $adCost;
        $this->diversityAdCost = $diversityAdCost;
        $this->featuredAdCost = $featuredAdCost;
        $this->siteTitle = $siteTitle;
        $this->contactEmail = $contactEmail;
        $this->accountingEmail = $accountingEmail;
        $this->infoEmail = $infoEmail;
        $this->receiptEmail = $receiptEmail;
        $this->logoPath = $logoPath;
    }

    public function setLogoPath($logoPath){
        $this->logoPath = $logoPath;
    }

    protected function isEmailValid($email)
    {
        $emailConstraint = new Email();
        $errors = $this->validator->validateValue($email, $emailConstraint);

        return count($errors) == 0;
    }

    public function sendForgotPasswordEmail($email, $token)
    {
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:forgotPassword.txt.twig', array(
            'token' => $token,
        ));
        $message = \Swift_Message::newInstance()
            ->setSubject(sprintf('Your %s Account Information', $this->siteTitle))
            ->setFrom($this->contactEmail)
            ->setTo($email)
            ->setBody($body)
        ;
        $this->mailer->send($message);
    }

    public function sendUnlimitedPlanSignupEmail($email, $data)
    {
        $now = new \DateTime();

        $data['start_date'] = $now->format('F d, Y');
        $data['end_date']   = $data['expired']->format('F d, Y');

        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:unlimitedPlanSignup.html.twig', $data);
        $this->sendHtmlEmail($email, sprintf('Your %s Unlimited Job Posting Plan Signup Confirmation', $this->siteTitle), $body);
    }

    public function sendInvoicePaidOnlineEmails($email, Invoice $invoice, array $response)
    {
        $subject = sprintf('Invoice paid online - Invoice Num %s', $invoice->getInvoice());
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:invoicePaidOnlineAdmin.html.twig', array(
            'invoice' => $invoice,
            'response' => $response,
            'email' => $email,
        ));
        $this->sendHtmlEmail($this->accountingEmail, $subject, $body);

        $subject = sprintf('%s - Invoice paid online - Invoice Num %s'
            , $this->siteTitle
            , $invoice->getInvoice()
        );
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:invoicePaidOnline.html.twig', array(
            'invoice' => $invoice,
            'response' => $response,
        ));
        $this->sendHtmlEmail($email, $subject, $body);
    }

    protected function getSwiftAttachmentPdf($template, $params, $filename)
    {
        $attachmentHtml = $this->templateEngine->render($template, $params);
        $attachmentPdf = $this->knpSnappy->getOutputFromHtml($attachmentHtml);
        $attachment = \Swift_Attachment::newInstance()
            ->setFilename($filename)
            ->setContentType('application/pdf')
            ->setBody($attachmentPdf)
        ;

        return $attachment;
    }

    public function sendNewJobsPostingsByCreditEmail(Employer $employer)
    {
        $message = \Swift_Message::newInstance();
        $message->setFrom($this->infoEmail);
        $message->setTo($employer->getBillingEmail() ?: $employer->getEmail());
        $message->setCc($this->contactEmail);
        $message->setSubject($this->siteTitle . ' New Job Posting');
        $message->setBody($this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:jobPostingCredit.txt.twig', array(
            'employer' => $employer,
        )));
        $this->mailer->send($message);
    }

    public function sendJobPackageReceipt(Payment $payment, $jobPackageCount)
    {
        $message = \Swift_Message::newInstance();
        $message->setFrom($this->receiptEmail);
        $message->setTo($payment->getEmployer()->getBillingEmail());
        $message->setContentType('text/html');
        $message->setCc($this->receiptEmail);
        $message->setSubject($this->siteTitle . ' Payment Receipt');
        $message->setBody($this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:jobPackageReceipt.html.twig', array(
            'payment' => $payment,
            'jobPackageCount' => $jobPackageCount,
        )));
        $this->mailer->send($message);
    }

    public function sendInvoiceEmail(InvoiceTrack $invoiceTrack)
    {
        $message = \Swift_Message::newInstance();
        $message->setFrom($this->receiptEmail);
        $message->setTo($invoiceTrack->getEmployer()->getBillingEmail());
        $message->setContentType('text/html');
        $message->setCc($this->receiptEmail);

        if ($invoiceTrack->getBillingType() == 'email') {
            $message->setSubject($this->siteTitle . ' Invoice -- Please print');
            $attachment = $this->getSwiftAttachmentPdf('NewRoadsMediaFrontendBundle:Email:invoiceAttachment.html.twig'
                ,  array('invoiceTrack' => $invoiceTrack)
                , sprintf('invoice-%d.pdf', $invoiceTrack->getId())
            );
            $message->attach($attachment);
            $message->setBody($this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:invoiceEmail.html.twig', array(
                'invoiceTrack' => $invoiceTrack,
            )));
        } else {
            $message->setSubject($this->siteTitle . ' Payment Receipt');
            $message->setBody($this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:invoiceCreditCard.html.twig', array(
                'invoiceTrack' => $invoiceTrack,
            )));
        }
        $this->mailer->send($message);
    }

    public function sendJobDeleteEmail(Job $job)
    {
        $employer = $job->getEmployer();
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:deleteJobPost.html.twig', array(
            'job' => $job,
            'employer' => $employer,
        ));
        $this->sendHtmlEmail($employer->getEmail(), $this->siteTitle . ' -- Job Post Deleted', $body, $this->contactEmail, $this->infoEmail);
    }

    public function sendJobSuspendEmail(Job $job)
    {
        $employer = $job->getEmployer();
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:suspendJobPost.html.twig', array(
            'job' => $job,
            'employer' => $employer,
        ));
        $this->sendHtmlEmail($employer->getEmail(), $this->siteTitle . ' -- Job Post Suspended', $body, $this->contactEmail, $this->infoEmail);
    }

    public function sendJobEditEmail(Job $job, $total, $addedDiversity, $addedFeatured, $addedIndustry, $newIndustries)
    {
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:editJobPost.txt.twig', array(
            'job' => $job,
            'total' => $total,
            'addedDiversity' => $addedDiversity,
            'addedFeatured' => $addedFeatured,
            'addedIndustry' => $addedIndustry,
            'newIndustries' => $newIndustries,
            'adCost' => $this->adCost,
            'featuredAdCost' => $this->featuredAdCost,
            'diversityAdCost' => $this->diversityAdCost,
        ));
        $this->sendTextEmail($job->getEmployer()->getEmail(), $this->siteTitle . ' Additional Charges Invoice', $body);
    }

    public function sendJobExpiringEmail(Job $job)
    {
        $message = \Swift_Message::newInstance();
        $message->setSubject('Reminder: Job posting to expire in 3 days');
        $message->setFrom($this->contactEmail);
        $message->setTo($job->getEmployer()->getEmail());
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:expiringJobNotification.txt.twig', array(
            'job' => $job,
            'employer' => $job->getEmployer(),
            'adCost' => $this->adCost,
        ));
        $message->setBody($body);
        $this->mailer->send($message);
    }

    public function sendJobListingEmail($fromEmail, $toEmail, $comments, Job $job)
    {
        $subject = sprintf('Re: %s Listing - %s'
            , $this->siteTitle
            , $job->getTitleOfPositionOpen()
        );
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:jobListing.html.twig', array(
            'fromEmail' => $fromEmail,
            'toEmail' => $toEmail,
            'comments' => $comments,
            'job' => $job,
        ));
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom($this->infoEmail);
        $message->setTo($toEmail);
        $message->setBody($body);
        $message->setContentType('text/html');
        $message->setReplyTo($fromEmail);
        $this->mailer->send($message);
    }

    /**
     * @param Notification $notification
     * @param Job[] $jobs
     */
    public function sendJobNotification(Notification $notification, $jobs)
    {
        $now = new \DateTime();
        $subject = sprintf('Your %s Job Notification for %s'
            , $this->siteTitle
            , $now->format('F d, Y')
        );
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:jobNotification.html.twig', array(
            'notification' => $notification,
            'jobs' => $jobs,
            'logoPath' => $this->logoPath,
	    'email' => $notification->getEmail()
        ));
        $this->sendHtmlEmail($notification->getEmail(), $subject, $body);
    }

    public function sendEmailApplication(Job $job, array $application, $attachmentPath = null)
    {
        $email = $job->getApplyEmail() && $this->isEmailValid($job->getApplyEmail())
            ? $job->getApplyEmail()
            : $job->getEmployer()->getEmail()
        ;
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:application.html.twig', array(
            'job' => $job,
            'application' => $application,
            'path' => $attachmentPath,
        ));

        $message = \Swift_Message::newInstance();
        $message->setSubject($this->siteTitle . ' Job Application for ' . $job->getTitleOfPositionOpen());
        $message->setFrom($this->contactEmail);
        $message->setTo($email);
        $message->setBody($body);
        $message->setContentType('text/html');
        $message->setReplyTo($application['email']);
        if ($attachmentPath) {
            $message->attach(\Swift_Attachment::fromPath($attachmentPath));
        }

        $this->mailer->send($message);
    }

    public function sendNewApplicationEmail(Application $application)
    {
        $job = $application->getJob();
        $email = $job->getApplyEmail() && $this->isEmailValid($job->getApplyEmail())
            ? $job->getApplyEmail()
            : $job->getEmployer()->getEmail()
        ;
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:newApplication.html.twig', array(
            'application' => $application,
        ));

        $message = \Swift_Message::newInstance();
        $message->setSubject($this->siteTitle . ' Job Application');
        $message->setFrom($this->contactEmail);
        $message->setTo($email);
        $message->setBody($body);
        $message->setContentType('text/html');
        $message->setReplyTo($application->getEmail());

        $this->mailer->send($message);
    }

    public function sendNewResumeEmail(Resume $resume, $loginJobSeekerUrl)
    {
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:newResume.txt.twig', array(
            'resume' => $resume,
	    'loginJobSeekerUrl' => $loginJobSeekerUrl
        ));
        $message = \Swift_Message::newInstance()
            ->setSubject($this->siteTitle . ' Resume Posting')
            ->setFrom($this->contactEmail)
            ->setTo($resume->getEmail())
            ->setBody($body)
        ;
        $this->mailer->send($message);
    }

    public function sendContactUsEmail($email, $subject, $body)
    {
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom($this->contactEmail);
        $message->setTo($this->contactEmail);
        $message->setBody('Email: ' . $email . PHP_EOL . PHP_EOL . $body);
        $message->setReplyTo($email);
        $this->mailer->send($message);
    }

    public function sendUnlimitedJobAdsEmail($email, $data)
    {
        $body = $this->templateEngine->render('NewRoadsMediaFrontendBundle:Email:unlimitedJobAds.html.twig', array(
            'data' => $data,
        ));
        $message = \Swift_Message::newInstance()
            ->setSubject($this->siteTitle . ' Unlimited Ads Request')
            ->setFrom($this->contactEmail)
            ->setTo($this->infoEmail)
            ->setReplyTo($email)
            ->setBody($body)
            ->setContentType('text/html')
        ;
        $this->mailer->send($message);
    }

    public function sendHtmlEmail($to, $subject, $body, $from = null, $cc = null)
    {
        if ($from === null) {
            $from = $this->contactEmail;
        }
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom($from);
        $message->setTo($to);
        $message->setBody($body);
        $message->setContentType('text/html');
        if ($cc !== null) {
            $message->setCc($cc);
        }
        $this->mailer->send($message);
    }

    public function sendTextEmail($to, $subject, $body, $from = null, $cc = null)
    {
        if ($from === null) {
            $from = $this->contactEmail;
        }
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom($from);
        $message->setTo($to);
        $message->setBody($body);
        if ($cc !== null) {
            $message->setCc($cc);
        }
        $this->mailer->send($message);
    }
}
