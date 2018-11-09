<?php

namespace NewRoadsMedia\FrontendBundle\Admin;

use NewRoadsMedia\FrontendBundle\Entity\BannerAd;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class BannerAdAdmin extends Admin
{
    public function configure()
    {
        $this->setTemplate('edit', 'NewRoadsMediaFrontendBundle:Admin:BannerAd/edit.html.twig');
        $this->setTemplate('reset', 'NewRoadsMediaFrontendBundle:Admin:BannerAd/reset.html.twig');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', array(
            ))
                ->add('name')
                ->add('adsNum', 'choice', [
                    'choices' => ['1'=>'1','2'=>'2'],
                    'label'   => 'Display one or two banner ads?',
                    'expanded' => true,
                    'help' => 'RULES: Ad 1 will display first. The ads can rotate every 5 seconds.'
                ])
            ->end()
            ->with('AD 1', ['class' => 'col-md-6'])
                ->add('link')
                ->add('image', 'file_upload', array(
                    'required' => false,
                    'directory' => '/media/banners',
                ))
                ->add('deleteImage', 'checkbox', array(
                    'required' => false,
                    'mapped' => false,
                ))
                ->add('isRawCode', null, array(
                    'label' => 'Use Raw Code',
                    'required' => false,
                ))
                ->add('rawCode', null, array(
                    'required' => false,
                ))
            ->end()
            ->with('AD 2', ['class' => 'col-md-6'])
                ->add('link2', null, array(
                    'label' => 'Link',
                    'required' => false,
                ))
                ->add('image2', 'file_upload', array(
                    'required' => false,
                    'directory' => '/media/banners',
                    'label' => 'Image',
                ))
                ->add('deleteImage2', 'checkbox', array(
                    'required' => false,
                    'mapped' => false,
                    'label' => 'Delete Image',
                ))
                ->add('isRawCode2', null, array(
                    'label' => 'Use Raw Code',
                    'required' => false,
                ))
                ->add('rawCode2', null, array(
                    'required' => false,
                    'label' => 'Raw Code',
                ))
            ->end()

            ->getFormBuilder()->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formMapper) {
                if ($event->getForm()->get('deleteImage')->getData()) {
                    /** @var BannerAd $bannerAd */
                    $bannerAd = $event->getData();
                    if ($bannerAd) {
                        $bannerAd->setImage(null);
                    }
                }
                if ($event->getForm()->get('deleteImage2')->getData()) {
                    /** @var BannerAd $bannerAd */
                    $bannerAd = $event->getData();
                    if ($bannerAd) {
                        $bannerAd->setImage2(null);
                    }
                }
            })
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('name')
            ->add('link')
            ->add('counter')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'reset' => array(
                        'template' => 'NewRoadsMediaFrontendBundle:Admin:BannerAd/resetField.html.twig'
                    ),
                ),
            ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array(
            'list',
            'edit',
        ));
        $collection->add('reset', $this->getRouterIdParameter() . '/reset');
    }
}
