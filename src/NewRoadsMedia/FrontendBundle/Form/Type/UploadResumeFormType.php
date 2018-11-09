<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class UploadResumeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var Resume $resume */
                $resume = $event->getData();
                if ($resume->getResumeFile()) {
                    $form = $event->getForm();
                    $form->add('deleteResumeFile', 'checkbox', array(
                        'mapped' => false,
                    ));
                }
            })

            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                /** @var Resume $resume */
                $resume = $event->getData();
                if ($form->get('resumeFile')->getData() == null && $resume->getResumeFile() == null) {
                    $form->addError(new FormError('Please attach your resume.'));
                }
            })
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\Resume',
        ));
    }

    public function getName()
    {
        return 'upload_resume';
    }
}