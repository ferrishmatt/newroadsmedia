<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\Employer;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminEmployerController extends CRUDController
{
    public function packagePlanAction()
    {
        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->admin->generateUrl('packagePlan'),
            ))
            ->add('id', 'integer', array(
                'label' => 'Employer ID',
                'required' => false,
            ))
            ->add('email', 'email', array(
                'label' => 'Employer Email',
                'required' => false,
                'constraints' => array(
                    new Email(array('message' => 'Invalid email address.')),
                ),
                'error_bubbling' => true,
            ))
            ->add('action', 'choice', array(
                'choices' => array(
                    'update' => 'Activate / Update Posting Plan',
                    'delete' => 'Delete Posting Plan',
                ),
                'multiple' => false,
                'expanded' => true,
                'data' => 'update',
                'required' => true,
            ))
            ->add('size', 'choice', array(
                'choices' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    10 => 10,
                    15 => 15,
                    20 => 20,
                    25 => 25,
                    30 => 30,
                    35 => 35,
                    40 => 40,
                    45 => 45,
                    50 => 50,
                ),
                'required' => true,
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('submit', 'submit', array(
                'label' => 'Save',
            ))
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
                $data = $event->getData();
                if (empty($data['email']) && empty($data['id'])) {
                    $form = $event->getForm();
                    $form->addError(new FormError('Either id or email must be entered.'));
                }
            })
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $employerManager = $this->get('journalismjobs.manager.employer');
            /** @var Employer $employer */
            $employer = null;
            $id = $form->get('id')->getData();
            $email = $form->get('email')->getData();

            if ($id) {
                $employer = $employerManager->find($id);
            }
            if (!$employer && $email) {
                $employer = $employerManager->findOneByEmail($email);
            }
            if ($employer) {
                $action = $form->get('action')->getData();
                if ($action == 'update') {
                    $size = $form->get('size')->getData();
                    $employer->setPackagePlan('Y');
                    $employer->addAdsRemaining($size);
                    $this->get('session')->getFlashBag()->add('sonata_flash_success', sprintf('%d %s added to employer "%s" (ID: %d) for a total of %d %s.'
                        , $size
                        , $size == 1 ? 'ad' : 'ads'
                        , $employer->getEmail()
                        , $employer->getId()
                        , $employer->getAdsRemaining()
                        , $employer->getAdsRemaining() == 1 ? 'ad' : 'ads'
                    ));
                } else if ($action == 'delete') {
                    $employer->setPackagePlan('N');
                    $employer->setAdsRemaining(0);
                    $this->get('session')->getFlashBag()->add('sonata_flash_success', sprintf('Package plan removed from employer "%s" (ID: %d).'
                        , $employer->getEmail()
                        , $employer->getId()
                    ));
                }
                $employerManager->save($employer);

                return $this->redirect($this->admin->generateUrl('packagePlan'));
            } else {
                $this->get('session')->getFlashBag()->add('sonata_flash_error', 'Employer not found.');
            }
        }

        return $this->render($this->admin->getTemplate('packagePlan'), array(
            'form' => $form->createView(),
            'object' => new Employer(),
        ));
    }

    public function grantResumeAccessAction($id = null)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        /** @var Employer $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }

        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->admin->generateUrl('grantResumeAccess', array('id' => $id)),
            ))
            ->add('action', 'hidden', array(
                'data' => 'grant',
            ))
            ->add('submit', 'submit', array(
                'label' => 'Grant Resume Search',
                'attr' => array(
                    'class' => 'btn btn-success',
                ),
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid() && $form->get('submit')->isClicked() && $form->get('action')->getData() == 'grant') {
            $object->setResumeAccess(true);
            $object->setSearchExpirationDate(new \DateTime('+5 weeks'));
            $object = $this->admin->update($object);
            $this->get('session')->getFlashBag()->add('sonata_flash_success', sprintf('Resume access granted to %s.', $object->getEmail()));

            return $this->redirect($this->admin->generateUrl('list'));
        }

        $formView = $form->createView();
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('grantResumeAccess'), array(
            'form' => $formView,
            'object' => $object,
        ));
    }

    public function resumeAccessAction()
    {
        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->admin->generateUrl('resumeAccess'),
            ))
            ->add('action', 'hidden', array(
                'data' => 'enable',
            ))
            ->add('submit', 'submit', array(
                'label' => 'Enable Global Resume Search'
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid() && $form->get('submit')->isClicked() && $form->get('action')->getData() == 'enable') {
            $this->get('journalismjobs.manager.employer')->getRepository()->createQueryBuilder('employer')
                ->update()
                ->set('employer.resumeAccess', true)
                ->where('employer.resumeAccess IS NULL OR employer.resumeAccess = 0')
                ->getQuery()
                ->execute()
            ;
            $this->get('session')->getFlashBag()->add('sonata_flash_success', 'Resume access granted globally.');

            return $this->redirect($this->admin->generateUrl('resumeAccess'));
        }
        $formView = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('resumeAccess'), array(
            'form' => $formView,
            'object' => new Employer(),
        ));
    }

    public function invoiceOptionAction()
    {
        $employerManager = $this->get('journalismjobs.manager.employer');

        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->admin->generateUrl('invoiceOption'),
                'attr' => array('novalidate' => 'novalidate'),
            ))
            ->add('email', 'email', array(
                'label' => 'Enter Email address:',
                'required' => true,
                'constraints' => array(
                    new Email(array('message' => 'Invalid email address.')),
                    new NotBlank(array('message' => 'Please enter an email address.')),
                ),
                'error_bubbling' => true,
            ))
            ->add('action', 'choice', array(
                'choices' => array(
                    'enable' => 'Enable',
                    'disable' => 'Disable',
                ),
                'multiple' => false,
                'expanded' => true,
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please select whether to enable or disable the invoice option.'))
                ),
                'error_bubbling' => true,
            ))
            ->add('submit', 'submit', array(
                'label' => 'Submit'
            ))
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($employerManager) {
                $data = $event->getData();
                $employer = $employerManager->findOneByEmail($data['email']);
                if (!$employer) {
                    $form = $event->getForm();
                    $form->addError(new FormError(sprintf('No employer found for "%s".', $data['email'])));
                }
            })
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid() && $form->get('submit')->isClicked()) {
            $this->get('journalismjobs.manager.employer')->getRepository()->createQueryBuilder('employer')
                ->update()
                ->set('employer.invoiceOption', ':invoiceOption')
                ->where('employer.email = :email')
                ->setParameter('invoiceOption', $form->get('action')->getData() == 'enable' ? 'allow' : null)
                ->setParameter('email', $form->get('email')->getData())
                ->getQuery()
                ->execute()
            ;
            $this->get('session')->getFlashBag()->add('sonata_flash_success', sprintf('Invoice option %s "%s".'
                , $form->get('action')->getData() == 'enable' ? 'granted to' : 'revoked from'
                , $form->get('email')->getData()
            ));

            return $this->redirect($this->admin->generateUrl('invoiceOption'));
        }
        $formView = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('invoiceOption'), array(
            'form' => $formView,
            'object' => new Employer(),
        ));
    }
}