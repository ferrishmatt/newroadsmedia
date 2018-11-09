<?php

namespace NewRoadsMedia\FrontendBundle\Form\Type;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AllowDeleteCollectionFormType extends AbstractType
{
    protected $index;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $index = &$this->index;
        $builder

            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use (&$index) {
                $data = $event->getData();
                $index = -1;
                if (!empty($data)) {
                    if ($data instanceof Collection) {
                        $keys = $data->getKeys();
                        $index = end($keys);
                    } else {
                        end($data);
                        $index = key($data);
                        reset($data);
                    }
                }
            })

            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use (&$index) {
                $data = $event->getData();
                $index = -1;
                if (!empty($data)) {
                    if ($data instanceof Collection) {
                        $keys = $data->getKeys();
                        $index = end($keys);
                    } else {
                        end($data);
                        $index = key($data);
                        reset($data);
                    }
                }
            })

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

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['collection_index'] = $this->index;
        $view->vars['prototype_name'] = $options['prototype_name'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $group = 'edit-profile';
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
        return 'allow_delete_collection';
    }

    public function getParent()
    {
        return 'collection';
    }
}