<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlankCollectionFormType extends AbstractType
{
    protected $validationGroups;

    public function __construct($validationGroups = array())
    {
        $this->validationGroups = $validationGroups;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $collection = $event->getData();
                $form = $event->getForm();

                foreach ($form as $row) {
                    /** @var FormInterface $row */
                    $hasContent = false;
                    foreach ($row as $field) {
                        /** @var FormInterface $field */
                        $data = $field->getData();
                        $hasContent = $data instanceof \Countable ? count($data) > 0 : !empty($data);
                        if ($hasContent) {
                            break;
                        }
                    }
                    if (!$hasContent) {
                        if (is_array($collection)) {
                            $index = array_search($row->getData(), $collection);
                            if ($index !== false) {
                                unset($collection[$index]);
                                $collection = array_values($collection);
                            }
                        } else if ($collection instanceof Collection) {
                            $collection->removeElement($row->getData());
                        }
                    }
                }
                $event->setData($collection);
            })
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $group = $this->validationGroups;
        $resolver->setDefaults(array(
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
            'options' => array(
                'label' => false,
                'validation_groups' => function(FormInterface $form) use ($group) {
                    $groups = array();

                    if ($group) {
                        foreach ($form as $child) {
                            /** @var FormInterface $child */
                            if (!in_array($group, $groups)) {
                                $data = $child->getData();
                                $hasContent = $data instanceof \Countable ? count($data) > 0 : !empty($data);
                                if ($hasContent) {
                                    $groups[] = $group;
                                }
                            }
                        }
                    }

                    return $groups;
                },
            ),
        ));
    }

    public function getName()
    {
        return 'blank_collection';
    }

    public function getParent()
    {
        return 'collection';
    }
}