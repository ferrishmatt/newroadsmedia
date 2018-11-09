<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Manager\ResumeManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\File;

class CreateJobSeekerAccountFormType extends AbstractType
{
    /** @var ResumeManager */
    protected $resumeManager;

    public function __construct(ResumeManager $resumeManager)
    {
        $this->resumeManager = $resumeManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $resumeManager = $this->resumeManager;
        $builder
        ->add('isPublic', 'hidden')
        ->add('resumeFile', 'file', array(
            'label' => 'Attach your Resume',
            'required' => true,
            'constraints' => array(
                new File(array(
                    'maxSize' => '1000k',
                    'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.',
                    'mimeTypes' => array(
                        'application/msword',
                        'application/pdf',
                        'application/rtf',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/zip',
                        'text/plain',
                        'text/rtf',
                    ),
                    'mimeTypesMessage' => 'Resume files can only be the following types: .DOCX, .DOC, .RTF, .TXT, .PDF',
                )),
            ),
            'mapped' => false,
        ))

        /*->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            //echo var_dump($event->getData(), true);
            $resume = $event->getData();
            
            if ($resume->getResumeFile()) {
                $form = $event->getForm();
                $form->add('deleteResumeFile', 'checkbox', array(
                    'mapped' => false,
                ));
            }
        })*/

        /*->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            @var Resume $resume
            $resume = $event->getData();
            if ($form->get('resumeFile')->getData() == null && $resume->getResumeFile() == null) {
                $form->addError(new FormError('Please attach your resume.'));
            }
        })*/
            ->add('name', 'text', array(
                'label' => 'Your name',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Name is required')),
                ),
                'error_bubbling' => true,
            ))
            ->add('email', 'email', array(
                'label' => 'Email address:',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Email address is required')),
                    new Email(array('message' => 'Valid Email is required')),
                ),
                'error_bubbling' => true,
            ))
            ->add('password', 'repeated', array(
                'label' => false,
                'type' => 'password',
                'first_options' => array(
                    'label' => 'Create password (up to 15 characters)',
                ),
                'second_options' => array(
                    'label' => 'Type the password again:',
                ),
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Password is required')),
                    new Length(array(
                        'min' => 6,
                        'minMessage' => 'Password must be at least {{ limit }} characters',
                        'max' => 15,
                        'maxMessage' => 'Password must be no more than {{ limit }} characters',
                    ))
                ),
                'error_bubbling' => true,
            ))
//            ->add('acceptTerms', 'checkbox', array(
//                'required' => false,
//                'value' => 1,
//                'constraints' => array(
//                    new EqualTo(array(
//                        'value' => 1,
//                        'message' => 'You must agree to the Terms and Conditions',
//                    ))
//                ),
//                'error_bubbling' => true,
//            ))
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($resumeManager) {
                $form = $event->getForm();
                $email = $form->get('email')->getData();
                if ($email && !$form->getErrors() && $resumeManager->findOneByEmail($email)) {
                    $form->get('email')->addError(new FormError('An account for the email address you entered already exists.'));
                }
            })
        ;
    }

    public function getName()
    {
        return 'create_job_seeker';
    }
}