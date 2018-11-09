<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type\Profile;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use NewRoadsMedia\FrontendBundle\Entity\ResumeCredential;
use NewRoadsMedia\FrontendBundle\Entity\ResumeEducation;
use NewRoadsMedia\FrontendBundle\Entity\ResumeEmployer;
use NewRoadsMedia\FrontendBundle\Entity\ResumeEmployerSpecialty;
use NewRoadsMedia\FrontendBundle\Entity\ResumeReference;
use NewRoadsMedia\FrontendBundle\Form\Type\AllowDeleteCollectionFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\BlankCollectionFormType;
use NewRoadsMedia\FrontendBundle\Validator\Constraints\WordCount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            /* Candidate Snapshot */
            ->add('name', 'text', array(
                'required' => true,
                'label' => 'Your Name',
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your name.')),
                ),
            ))
            ->add('image', 'file', array(
                'mapped' => false,
                'required' => false,
                'label' => 'Attach Profile Photo (optional)',
                'constraints' => array(
                    new File(array(
                        'maxSize' => '1000k',
                        'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.',
                        'mimeTypes' => array(
                            'image/jpg',
                            'image/jpeg',
                            'image/gif',
                            'image/png',
                        ),
                        'mimeTypesMessage' => 'Images can only be the following types: .GIF, .JPG, .PNG',
                    )),
                ),
            ))
            ->add('deleteImage', 'checkbox', array(
                'mapped' => false,
                'required' => false,
                'label' => 'Delete?',
            ))
            ->add('title', 'text', array(
                'label' => 'Current or Recent Job Title',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter a job title.')),
                ),
            ))
            ->add('company', 'text', array(
                'label' => 'Company',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('city', 'text', array(
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('currentState', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => 'Select State',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->groupBy('l.locationDescription')->orderBy('l.displayOrder', 'ASC');
                },
                'required' => true,
                'label' => 'Your Current Location',
                'multiple' => false,
                'expanded' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('contactEmail', 'email', array(
                'label' => 'Email Contact',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                    new Email(array('message' => 'Please enter a valid email.')),
                ),
            ))
            ->add('phone', 'text', array(
                'required' => true,
                'label' => 'Phone',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('phoneArea', 'text', array(
                'label' => false,
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('website', 'url', array(
                'required' => false,
                'label' => 'Professional Website',
            ))
            ->add('isAvailableImmediately', 'checkbox', array(
                'label' => 'Immediately',
                'required' => false,
            ))
            ->add('dateAvailable', 'datetime', array(
                'label' => 'When are you available to start work?',
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'required' => false,
                'invalid_message' => 'Please enter a valid date.',
                'attr' => array('placeholder' => 'Enter start date'),
            ))
            ->add('resumeJobObjective', 'textarea', array(
                'required' => true,
                'label' => 'Career Objective',
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('currentJobStatusChoice', 'choice', array(
                'mapped' => false,
                'label' => 'What is Your Job Status?',
                'empty_value' => '',
                'choices' => array(
                    'Employed' => 'Employed',
                    'Looking for Work' => 'Looking for Work',
                    'On Sabbatical' => 'On Sabbatical',
                    'Other' => 'Other',
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'This field cannot be blank.')),
                ),
            ))
            ->add('currentJobStatus', 'text', array(
                'label' => false,
                'required' => false,
                'constraints' => array(
                    new WordCount(array('limit' => 10)),
                ),
            ))
            ->add('industries', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Industry',
                'property' => 'industry',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ))
            ->add('specialty', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Specialty',
                'empty_value' => '',
                'property' => 'specialty',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('focus', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Focus',
                'property' => 'focus',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('f')->orderBy('f.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ))

            /* Work History */
            ->add('employers', new AllowDeleteCollectionFormType(), array(
                'type' => new ResumeEmployerFormType(),
                'prototype_name' => '__employer__',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
            ))

            /* Certification */
            ->add('certification', new ResumeCertificationFormType(), array(
                'label' => false,
            ))
            ->add('credentials', new BlankCollectionFormType('edit-profile'), array(
                'type' => new ResumeCredentialFormType(),
            ))

            /* Honors & Achievements */
            ->add('resumeHonors', 'textarea', array(
                'label' => false,
            ))

            /* Education */
            ->add('educations', new AllowDeleteCollectionFormType(), array(
                'type' => new ResumeEducationFormType(),
                'prototype_name' => '__education__',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
            ))

            /* References */
            ->add('references', new AllowDeleteCollectionFormType(), array(
                'type' => new ResumeReferenceFormType(),
                'prototype_name' => '__reference__',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
            ))

            // by default a lot of the *-to-many fields need to have multiple rows of blanks
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $resume = $event->getData();
                if ($resume instanceof Resume) {
                    if ($resume->getEmployers()->count() < 1) {
                        $resume->addEmployer(new ResumeEmployer());
                    }
                    if ($resume->getReferences()->count() < 1) {
                        $resume->addReference(new ResumeReference());
                    }
                    if ($resume->getEducations()->count() < 1) {
                        $resume->addEducation(new ResumeEducation());
                    }
                    foreach ($resume->getEmployers() as $employer) {
                        if ($employer->getSpecialties()->count() < 1) {
                            $employer->addSpecialty(new ResumeEmployerSpecialty());
                        }
                    }
                    if ($resume->getCredentials()->count() < 1) {
                        $resume->addCredential(new ResumeCredential());
                    }
                }
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $choice = isset($data['currentJobStatusChoice']) ? $data['currentJobStatusChoice'] : null;
                if ($choice != 'Other') {
                    $data['currentJobStatus'] = $choice;
                    $event->setData($data);
                }
            })
        ;
    }

    protected function hasErrors(FormInterface $form)
    {
        if ($form->getErrors()) {
            return true;
        }
        foreach ($form as $child) {
            if ($this->hasErrors($child)) {
                return true;
            }
        }

        return false;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $sections = array(
            'CandidateSnapshot' => array('name', 'title', 'company', 'currentState', 'phone', 'phoneArea', 'website', 'resumeJobObjective', 'currentJobStatus', 'currentJobStatusChoice', 'industries', 'specialty', 'focus'),
            'WorkHistory' => array('employers'),
            'Certifications' => array('certification', 'credentials'),
            'HonorsAchievements' => array('resumeHonors'),
            'Education' => array('educations'),
            'References' => array('references'),
        );
        foreach ($sections as $section => $fields) {
            $hasErrors = false;
            foreach ($fields as $field) {
                if ($form->has($field) && $this->hasErrors($form->get($field))) {
                    $hasErrors = true;
                    break;
                }
            }
            $view->vars['hasErrors' . $section] = $hasErrors;
        }
    }

    public function getName()
    {
        return 'profile_edit_resume';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\Resume',
            'validation_groups' => array('edit-profile'),
        ));
    }
}