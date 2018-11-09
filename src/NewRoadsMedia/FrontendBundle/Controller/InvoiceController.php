<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\Invoice;

use NewRoadsMedia\FrontendBundle\Form\Type\InvoiceFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\SignupUnlimitedPostingFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvoiceController extends Controller
{
    /**
     * @Route("/payments", name="Invoice")
     * @Template()
     */
    public function invoiceAction()
    {
        $form = $this->createForm(new InvoiceFormType(), null, array(
            'method' => 'POST',
            'action' => $this->generateUrl('Invoice'),
            'attr' => array('novalidate' => 'novalidate'),
        ));
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $paymentManager = $this->get('journalismjobs.manager.payment');
            $payment = $paymentManager->create();
            $payment->setPaymentMethod('creditcard');
            $payment->setCreditCardNumber($form->get('creditNumber')->getData());
            $payment->setExpirationDateMonth($form->get('creditMonth')->getData());
            $payment->setExpirationDateYear($form->get('creditYear')->getData());
            $payment->setNameOnCard($form->get('name')->getData());
            $payment->setApproval(false);
            $payment->setChargeAmount($form->get('amount')->getData());
            $payment->setTransactionDate(new \DateTime());

            $response = $paymentManager->chargeCreditCard($payment, $form->get('cardCode')->getData(), $form->get('company')->getData());
            if ($response['response_code'] == 1) {
                $invoiceManager = $this->get('journalismjobs.manager.invoice');
                $invoice = $invoiceManager->create();
                $invoice->setName($form->get('name')->getData());
                $invoice->setAddress($form->get('address')->getData());
                $invoice->setCity($form->get('city')->getData());
                $invoice->setState($form->get('state')->getData());
                $invoice->setZip($form->get('zip')->getData());
                $invoice->setCompany($form->get('company')->getData());
                $invoice->setSpecialNotes($form->get('specialInstructions')->getData());
                $invoice->setAmount($payment->getChargeAmount());
                $invoice->setCreationDate(new \DateTime());
                $invoice->setBillingMethod($payment->getPaymentMethod());
                $invoice->setAmountPaid($payment->getChargeAmount());
                $invoice->setWhenPaid(new \DateTime());
                $invoice->setInvoice($form->get('invoice')->getData());
                $invoiceManager->persist($invoice);
                $paymentManager->save($payment);

                $this->get('journalismjobs.frontend.mailer')->sendInvoicePaidOnlineEmails($form->get('email')->getData(), $invoice, $response);

                return $this->render('NewRoadsMediaFrontendBundle:Invoice:confirmation.html.twig', array(
                    'response' => $response,
                    'invoice' => $invoice,
                ));
            } else {
                $this->get('session')->getFlashBag()->add('error', $response['response_msg']);
                $paymentManager->save($payment);
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/sign-up-for-unlimited-job-posting-plan", name="SignupUnlimitedPosting")
     * @Template()
     */
    public function signupUnlimitedPostingAction()
    {
        $amount = 500;

        $form = $this->createForm(new SignupUnlimitedPostingFormType(), null, array(
            'method' => 'POST',
            'action' => $this->generateUrl('SignupUnlimitedPosting'),
            'attr' => array('novalidate' => 'novalidate'),
        ));
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            // $this->get('session')->getFlashBag()->add('notice', 'Your account has been created. Complete the fields below to post your ad.');


            $paymentManager = $this->get('journalismjobs.manager.payment');
            $payment = $paymentManager->create();
            $payment->setPaymentMethod('creditcard');
            $payment->setCreditCardNumber($form->get('creditNumber')->getData());
            $payment->setExpirationDateMonth($form->get('creditMonth')->getData());
            $payment->setExpirationDateYear($form->get('creditYear')->getData());
            $payment->setNameOnCard($form->get('name')->getData());
            $payment->setApproval(false);
            $payment->setChargeAmount($amount);
            $payment->setTransactionDate(new \DateTime());

            $response = $paymentManager->chargeCreditCard($payment, $form->get('cardCode')->getData(), $form->get('company')->getData());
            if ($response['response_code'] == 1) {
                $invoiceManager = $this->get('journalismjobs.manager.invoice');
                $invoice = $invoiceManager->create();
                $invoice->setName($form->get('billing_name')->getData());
                $invoice->setAddress($form->get('address')->getData());
                $invoice->setCity($form->get('city')->getData());
                $invoice->setState($form->get('state')->getData());
                $invoice->setZip($form->get('zip')->getData());
                $invoice->setCompany($form->get('company')->getData());
                $invoice->setSpecialNotes($form->get('specialInstructions')->getData());
                $invoice->setAmount($payment->getChargeAmount());
                $invoice->setCreationDate(new \DateTime());
                $invoice->setBillingMethod($payment->getPaymentMethod());
                $invoice->setAmountPaid($payment->getChargeAmount());
                $invoice->setWhenPaid(new \DateTime());
                // $invoice->setInvoice($form->get('invoice')->getData());
                $invoiceManager->persist($invoice);
                $paymentManager->save($payment);

                $employerManager = $this->get('journalismjobs.manager.employer');
                $employer = $employerManager->create();
                $employer->setName($form->get('name')->getData());
                $employer->setEmail($form->get('email')->getData());
                $employer->setPhone($form->get('phone')->getData());
                $employer->setMembershipExpirationDate(new \DateTime('+1 year'));
                $employerManager->save($employer);


                $this->get('journalismjobs.frontend.mailer')->sendUnlimitedPlanSignupEmail(
                    $form->get('email')->getData(),
                    [
                        'expired'  => $employer->getMembershipExpirationDate(),
                        'response' => $response,
                        'amount'   => $amount,
                        'jobs_page_url' => $form->get('portal_address')->getData(),
                        'cc_type'  => $form->get('creditCard')->getData()->getCardName(),
                        'billing_info' => [
                            $form->get('billing_name')->getData(),
                            $form->get('billing_company')->getData(),
                            $form->get('address')->getData(),
                            $form->get('city')->getData() . ', ' . $form->get('state')->getData() . ' ' . $form->get('zip')->getData(),
                            $form->get('email')->getData()
                        ]
                    ]
                );

                return $this->render('NewRoadsMediaFrontendBundle:Invoice:signupConfirmation.html.twig', array(
                    'response' => $response,
                    'invoice' => $invoice,
                ));
            } else {
                $this->get('session')->getFlashBag()->add('error', $response['response_msg']);
                $paymentManager->save($payment);
            }
        }

        return array(
            'form' => $form->createView(),
            'amount' => $amount
        );
    }

    /**
     * @Route("/signup_test")
     */
    public function signupTestAction()
    {
        $this->get('journalismjobs.frontend.mailer')->sendUnlimitedPlanSignupEmail('erlanbekenov@gmail.com', new \DateTime('+1 year'));
        die('Done');
    }
}