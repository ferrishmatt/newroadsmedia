<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use NewRoadsMedia\FrontendBundle\Entity\Duration;
use NewRoadsMedia\FrontendBundle\Entity\Focus;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Manager\JobManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostNewJobFormType extends AbstractType
{
    /** @var \NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager  */
    protected $durationManager;

    /** @var JobManager */
    protected $jobManager;

    /** @var ObjectManager */
    protected $focusManager;

    protected $showFrontPage = true;

    public function __construct(ObjectManager $durationManager, JobManager $jobManager, ObjectManager $focusManager)
    {
        $this->durationManager = $durationManager;
        $this->jobManager = $jobManager;
        $this->focusManager = $focusManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isFrontPage = false;
        $showFrontPage = &$this->showFrontPage;
        $durationManager = $this->durationManager;
        $jobManager = $this->jobManager;
        $focusManager = $this->focusManager;

        $builder
            ->add('company', 'text', array(
                'required' => false,
            ))
            ->add('titleOfPositionOpen', 'text', array(
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Job Headline is required!')),
                ),
                'error_bubbling' => true,
            ))
            ->add('jobCity', 'text')
            ->add('position', 'position', array(
                'empty_value' => '',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Position is required!')),
                ),
                'error_bubbling' => true,
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
            ->add('location', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Location',
                'empty_value' => '',
                'label' => 'Select Location',
                'property' => 'locationDescription',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')->groupBy('l.locationDescription')->orderBy('l.displayOrder', 'ASC');
                },
                'required' => true,
            ))
            ->add('jobType', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:JobType',
                'empty_value' => '',
                'label' => 'Job Status',
                'property' => 'jobType',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('jt')->orderBy('jt.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('salary', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Salary',
                'empty_value' => '',
                'label' => 'Salary',
                'property' => 'salary',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')->orderBy('s.displayOrder', 'ASC');
                },
                'required' => false,
            ))
            ->add('customSalary', 'text', array(
                'label' => 'Salary Range',
                'required' => false,
            ))
            ->add('website', 'text', array(
                'required' => false,
            ))
            ->add('adDescription', 'textarea', array(
                'required' => true,
                'error_bubbling' => true,
            ))
            ->add('applyType', 'choice', array(
                'choices' => array(
                    'a' => 'Option 1',
                    'b' => 'Option 2',
                    'email' => 'Option 3',
                    'url' => 'Option 4',
                ),
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ))
            ->add('applyEmail', 'email', array(
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('applyUrl', 'url', array(
                'error_bubbling' => true,
                'required' => true,
            ))
            ->add('isDiversity', 'checkbox', array(
                'required' => false,
            ))
            ->add('isFrontPage', 'checkbox', array(
                'required' => false,
            ))
            ->add('focus', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Focus',
                'error_bubbling' => true,
                'label' => 'What area of STEM will this position focus on? (check all that apply):',
                'property' => 'focus',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('f')->orderBy('f.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ))
            ->add('industries', 'entity', array(
                'class' => 'NewRoadsMediaFrontendBundle:Industry',
                'error_bubbling' => true,
                'property' => 'industry',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.displayOrder', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ))
            ->add('image', 'file', array(
                'mapped' => false,
                'required' => false,
                'label' => 'Company Logo',
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
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($durationManager, $jobManager, &$isFrontPage, &$showFrontPage) {
                $job = $event->getData();
                $form = $event->getForm();
                if ($job instanceof Job) {
                    $isFrontPage = $job->getIsFrontPage();
                    if (!$isFrontPage && $jobManager->getAvailableFrontPageAdCount() <= 0) {
                        $showFrontPage = false;
                        $form->add('isFrontPage', 'hidden', array(
                            'data' => 0,
                        ));
                    }
                }
                if (!$job instanceof Job || $job->isNew()) {
                    if ($durationManager->getCount() > 1) {
                        $form->add('duration', 'entity', array(
                            'class' => 'NewRoadsMediaFrontendBundle:Duration',
                            'property' => 'duration',
                            'query_builder' => function(EntityRepository $er) {
                                return $er->createQueryBuilder('d')->orderBy('d.displayOrder', 'ASC');
                            },
                            'required' => true,
                            'mapped' => false,
                            'label' => 'Ad Duration',
                        ));
                    }
                    $form->add('submit', 'submit', array(
                        'label' => 'Next -> Preview Ad',
                    ));
                } else {
                    $form->add('submit', 'submit', array(
                        'label' => 'Save Changes',
                    ));
                }
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($durationManager, $jobManager, $focusManager, &$isFrontPage) {
                $form = $event->getForm();
                $job = $event->getData();
                if (!$isFrontPage && !empty($job['isFrontPage']) && $jobManager->getAvailableFrontPageAdCount() <= 0) {
                    $form->addError(new FormError('Sorry, no more featured front page slots are available.'));
                    $job['isFrontPage'] = 0;
                    $event->setData($job);
                }
                $focuses = $focusManager->findAll();
                if (!empty($focuses) && empty($job['focus']) && empty($job['specialty'])) {
                    $form->addError(new FormError('Please select either a STEM Focus or a Non-STEM Focus.'));
                }
            })
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($durationManager, $focusManager) {
                $form = $event->getForm();
                $job = $event->getData();
                if ($job instanceof Job) {
                    if ($job->isIncomplete()) {
                        /** @var Duration $duration */
                        $duration = null;
                        if ($form->has('duration')) {
                            $duration = $form->get('duration')->getData();
                        } else if ($durationManager->getCount() == 1) {
                            $durations = $durationManager->findAll();
                            if ($durations) {
                                $duration = array_shift($durations);
                            }
                        }
                        if ($duration) {
                            $job->setDuration($duration->getDuration());
                        } else {
                            $job->setDuration('35 days');
                        }
                    }
                }
            })
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['showFrontPage'] = $this->showFrontPage;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NewRoadsMedia\FrontendBundle\Entity\Job',
            'validation_groups' => function(FormInterface $form) {
                $groups = array('Default', 'post-new-job');
                switch ($form->get('applyType')->getData()) {
                    case 'email':
                        $groups[] = 'post-new-job-apply-type-email';
                        break;
                    case 'url':
                        $groups[] = 'post-new-job-apply-type-url';
                        break;
                }

                return $groups;
            },
            'error_bubbling' => true,
        ));
    }

    public function getName()
    {
        return 'post_new_job';
    }
}