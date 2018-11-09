<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Validator\Constraints\WordCount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Uploadcare\Api;

class NonRegisteredApplicationFormType extends AbstractType
{
    /** @var Api */
    protected $uploadCare;

    public function __construct($uploadCare)
    {
        $this->uploadCare = $uploadCare;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your name.')),
                ),
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('email', 'email', array(
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your email address.')),
                    new Email(array('message' => 'Please enter a valid email address.')),
                ),
                'error_bubbling' => true,
                'required' => true,
                'label' => 'Email Address',
            ))
            ->add('coverLetter', 'textarea', array(
                'label' => 'Your Cover Letter (450 words max).',
                'constraints' => array(
                    new WordCount(array(
                        'limit' => 500,
                        'message' => 'There is a %limit% word limit on your cover letter.'
                    )),
                ),
                'error_bubbling' => true,
            ))
            ->add('captcha', 'captcha', array(
                'mapped' => false,
                'error_bubbling' => true,
                'label' => 'Enter Captcha Text',
            ))
        ;
        if (!$options['is_mobile']) {
            $builder
                ->add('resumeText', 'textarea', array(
                    'label' => false,
                    'required' => true,
                    'constraints' => array(
                        new NotBlank(array(
                            'message' => $options['upload'] ? 'Please attach a resume file or paste your resume into the textbox.' : 'Please enter your resume into the textbox.',
                            'groups' => array('ResumeText'),
                        )),
                        new WordCount(array(
                            'message' => 'There is a %length% word limit on your resume.',
                            'limit' => 1000,
                        )),
                    ),
                    'error_bubbling' => true,
                ))
            ;
        }

        if ($options['upload']) {
            $uploadCare = $this->uploadCare;
            $builder
                ->add('resumeFile', 'hidden')
                ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($uploadCare) {
                    $form = $event->getForm();
                    if ($form->getErrors()) {
                        return;
                    }

                    $fileId = $form->get('resumeFile')->getData();
                    if (!$fileId) {
                        if ($form->has('resumeText') && $form->get('resumeText')->getData()) {
                            return;
                        }

                        $form->addError(new FormError('Please attach a resume file.'));
                        return;
                    }

                    $file = $uploadCare->getFile($fileId);
                    $fileInfo = $file->updateInfo();

                    $mimeType = strtolower($fileInfo['mime_type']);
                    $mimeTypes = array(
                        'application/msword',
                        'application/pdf',
                        'application/rtf',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/zip',
                        'text/plain',
                        'text/rtf',
                        'application/x-download',
                        'application/force-download',
                        'application/x-msdownload',
                        'application/octet-stream',
                        'application/download',
                        'file/x-msdownload',
                    );
                    if (!in_array($mimeType, $mimeTypes)) {
                        $form->addError(new FormError('Resume files can only be the following types: .DOCX, .DOC, .RTF, .TXT, .PDF'));
                        return;
                    }

                    $extensionError = new FormError('Resume files can only be the following types: .DOCX, .DOC, .RTF, .TXT, .PDF');
                    $parts = explode('.', $fileInfo['original_filename']);
                    if (!$parts) {
                        $form->addError($extensionError);
                        return;
                    }

                    $extension = array_pop($parts);
                    $extensions = array('docx', 'doc', 'rtf', 'txt', 'pdf');
                    if (!in_array($extension, $extensions)) {
                        $form->addError($extensionError);
                        return;
                    }
                })
            ;
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['upload'] = $options['upload'];
        $view->vars['is_mobile'] = $options['is_mobile'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => function(FormInterface $form) {
                $groups = array('Default');
                if (!$form->has('resumeFile') || $form->get('resumeFile')->getData() === null) {
                    $groups[] = 'ResumeText';
                }

                return $groups;
            },
            'upload' => true,
            'is_mobile' => false,
        ));
    }

    public function getName()
    {
        return 'non_registered_application';
    }
}
