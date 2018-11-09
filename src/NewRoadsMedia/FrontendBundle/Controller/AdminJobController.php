<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

class AdminJobController extends CRUDController
{
    public function crawlerReportAction()
    {
        $data = array(
            'startDate' => new \DateTime('first day of this month'),
            'endDate' => new \DateTime('last day of this month'),
        );
        $form = $this->createFormBuilder($data, array(
                'csrf_protection' => false,
                'method' => 'GET',
            ))
            ->add('startDate', 'date', array(
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'label' => 'Start Date',
                'required' => true,
                'attr' => array('class' => 'date-picker')
            ))
            ->add('endDate', 'date', array(
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'label' => 'End Date',
                'required' => true,
                'attr' => array('class' => 'date-picker')
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        $report = $this->get('journalismjobs.manager.job')->getRepository()->getCrawlerReport($form->get('startDate')->getData(), $form->get('endDate')->getData());

        return $this->render($this->admin->getTemplate('crawler_report'), array(
            'form' => $form->createView(),
            'report' => $report,
        ));
    }
}