<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminBannerAdController extends CRUDController
{
    public function resetAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);
        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->admin->generateUrl('reset', array('id' => $id)),
            ))
            ->add('submit', 'submit', array(
                'label' => 'Reset Counter',
                'attr' => array(
                    'class' => 'btn btn-success',
                ),
            ))
            ->getForm()
        ;

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $this->get('journalismjobs.manager.banner_ad')->reset($id);
            $this->get('session')->getFlashBag()->add('sonata_flash_success', sprintf('Counter reset for %s.', $object->getName()));

            return $this->redirect($this->admin->generateUrl('list'));
        }

        return $this->render($this->admin->getTemplate('reset'), array(
            'form' => $form->createView(),
            'object' => $object,
        ));
    }
}