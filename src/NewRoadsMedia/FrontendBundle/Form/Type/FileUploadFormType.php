<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use NewRoadsMedia\FrontendBundle\Form\DataTransformer\FileToFilenameTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FileUploadFormType extends AbstractType
{
    /** @var string */
    protected $baseDirectory;

    /** @var FileToFilenameTransformer */
    protected $fileToFilenameTransformer;

    public function __construct($baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fileToFilenameTransformer = new FileToFilenameTransformer($this->baseDirectory, $options['directory'], $options['is_random_name']);
        $builder->addModelTransformer($fileToFilenameTransformer);
//        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($fileToFilenameTransformer) {
//            $file = $event->getData();
//            if ($file instanceof UploadedFile) {
//                $path = $fileToFilenameTransformer->getUploadedPath($file);
//                if (file_exists($path)) {
//                    $form = $event->getForm();
//                    $form->addError(new FormError(sprintf('The file "%s" already exists.', $file->getClientOriginalName())));
//                }
//            }
//        });
        $this->fileToFilenameTransformer = $fileToFilenameTransformer;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['file_type'] = null;
        $view->vars['type'] = 'file';
        if ($options['file_type'] == 'image') {
            $file = $view->vars['value'];
            if ($file instanceof File) {
                $view->vars['file_type'] = 'image';
                $view->vars['src'] = $this->fileToFilenameTransformer->getFilename($file);
            }
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view
            ->vars['multipart'] = true
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'directory' => null,
            'compound' => false,
            'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'empty_data' => null,
            'file_type' => 'image',
            'is_random_name' => false,
        ));
    }

    public function getName()
    {
        return 'file_upload';
    }
}