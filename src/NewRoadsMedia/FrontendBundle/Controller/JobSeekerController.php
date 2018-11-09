<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use Doctrine\ORM\EntityRepository;
use NewRoadsMedia\FrontendBundle\Entity\JobBasket;
use NewRoadsMedia\FrontendBundle\Entity\Notification;
use NewRoadsMedia\FrontendBundle\Entity\Resume;
use NewRoadsMedia\FrontendBundle\Form\JobNotificationForm;
use NewRoadsMedia\FrontendBundle\Form\Type\JobBasketFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\JobNotificationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Count;

class JobSeekerController extends Controller
{
	/**
   	 * @Route("/job-seeker/", name="JobSeekerFolder")
     * @Template()
	 */
    public function indexAction()
    {
        /** @var Resume $resume */
        $resume = $this->getUser();
        $form = $this->createForm(new JobBasketFormType(), null, array(
            'method' => 'POST',
            'action' => $this->generateUrl('ViewJobBasket'),
            'resume' => $resume,
            'max' => 5,
        ));

        $resumeForm = $this->createFormBuilder($resume, array(
                'method' => 'POST',
                'action' => $this->generateUrl('JobSeekerFolder'),
                'attr' => array('id' => 'resume-form'),
            ))
            ->add('isPublic', 'checkbox', array(
                'required' => false,
            ))
            ->getForm()
        ;
        $resumeForm->handleRequest($this->getRequest());
        if ($resumeForm->isValid()) {
            $this->get('journalismjobs.manager.resume')->save($resume);
            $this->get('session')->getFlashBag()->add('notice', 'Your changes have been saved.');

            return $this->redirect($this->generateUrl('JobSeekerFolder'));
        }

        return array(
            'resume' => $this->getUser(),
            'form' => $form->createView(),
            'resumeForm' => $resumeForm->createView(),
            'notificationCount' => $this->get('journalismjobs.manager.notification')->getActiveNotificationCountForResume($resume),
        );
    }

    /**
     * @Route("/job-seeker/job-basket/display", name="ViewJobBasket")
     * @Template()
     */
    public function jobBasketViewAction()
    {
        /** @var Resume $resume */
        $resume = $this->getUser();

        if ($resume->getJobBaskets()->count() == 0) {
            $this->get('session')->getFlashBag()->add('notice', 'You have 0 jobs in your job basket.');

            return $this->redirect($this->generateUrl('JobSeekerFolder'));
        }

        $form = $this->createForm(new JobBasketFormType(), null, array(
            'method' => 'POST',
            'action' => $this->generateUrl('ViewJobBasket'),
            'resume' => $resume,
        ));
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $data = $form->getData();
            $jobBasketManager = $this->get('journalismjobs.manager.job_basket');
            if ($form->get('delete')->isClicked()) {
                $jobBasketIds = $data['deleteJobBaskets'];
                foreach ($resume->getJobBaskets() as $jobBasket) {
                    if (in_array($jobBasket->getId(), $jobBasketIds)) {
                        $jobBasketManager->remove($jobBasket);
                    }
                }
                $jobBasketManager->flush();
                if ($resume->getJobBaskets()->count() == 0) {
                    $this->get('session')->getFlashBag()->add('notice', 'All jobs have been deleted from your Job Basket.');

                    return $this->redirect($this->generateUrl('JobSeekerFolder'));
                }
                $this->get('session')->getFlashBag()->add('notice', 'The job(s) you selected have been deleted.');
            } else if ($form->get('apply')->isClicked()) {
                $jobBasketIds = $data['applyJobBaskets'];
                foreach ($resume->getJobBaskets() as $jobBasket) {
                    if (in_array($jobBasket->getId(), $jobBasketIds)) {
                        $jobBasket->setHasApplied(true);
                        $jobBasketManager->persist($jobBasket);
                    }
                }
                $jobBasketManager->flush();
                $this->get('session')->getFlashBag()->add('notice', sprintf('%d job%s been applied for.'
                    , count($jobBasketIds)
                    , count($jobBasketIds) == 1 ? ' has' : 's have'
                ));
            }

            return $this->redirect($this->generateUrl('ViewJobBasket'));
        }

        return array(
            'resume' => $resume,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/job-seeker/job-basket/{jobId}/add", name="AddJobBasket")
     */
    public function jobBasketAddAction($jobId)
    {
        $job = $this->get('journalismjobs.manager.job')->find($jobId);
        if (!$job) {
            throw new NotFoundHttpException(sprintf('Job %d not found for job basket.', $jobId));
        }

        /** @var Resume $resume */
        $resume = $this->getUser();
        foreach ($resume->getJobBaskets() as $jobBasket) {
            if ($jobBasket->getJob() == $job) {
                $this->get('session')->getFlashBag()->add('notice', 'This job has already been added to your job basket.');

                return $this->redirect($this->generateUrl('ViewJobBasket'));
            }
        }

        $jobBasketManager = $this->get('journalismjobs.manager.job_basket');
        /** @var JobBasket $jobBasket */
        $jobBasket = $jobBasketManager->create();
        $jobBasket->setJob($job);
        $jobBasket->setResume($this->getUser());
        $jobBasketManager->save($jobBasket);
        $this->get('session')->getFlashBag()->add('notice', 'Job has been added to your job basket.');

        return $this->redirect($this->generateUrl('ViewJobBasket'));
    }

    /**
     * @Route("/job-seeker/job-notification", name="JobNotification")
     * @Template()
     */
    public function jobNotificationAction()
    {
        $form = $this->createForm(new JobNotificationFormType(), null, array(
            'method' => 'POST',
            'action' => $this->generateUrl('JobNotification'),
            'attr' => array('novalidate' => 'novalidate'),
        ));
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            /** @var JobNotificationForm $jobNotificationForm */
            $jobNotificationForm = $form->getData();
            $em = $this->getDoctrine()->getManager();
            if ($jobNotificationForm->getSubscriptionStatus() == 'unsubscribe') {
                $em
                    ->createQuery('DELETE FROM NewRoadsMediaFrontendBundle:Notification n WHERE n.email = :email')
                    ->setParameter('email', $this->getUser()->getEmail())
                    ->execute();
                $this->get('session')->getFlashBag()->add('notice', 'You have been unsubscribed from all job alerts.');
            } else {
                foreach ($jobNotificationForm->getIndustries() as $industry) {
                    $notification = new Notification();
                    $notification->setNotificationId(-1);
                    $notification->setIndustry($industry);
                    $notification->setLocation($jobNotificationForm->getLocation());
                    $notification->setPosition($jobNotificationForm->getPosition());
                    $notification->setJobType($jobNotificationForm->getJobType());
                    $notification->setExpiration(new \DateTime('3010-01-01'));
                    $notification->setEmail($this->getUser()->getEmail());
                    $notification->setResume($this->getUser());
                    foreach ($jobNotificationForm->getFocus() as $focus) {
                        $notification->addFocus($focus);
                    }
                    $notification->setHasNonFocused($jobNotificationForm->getNonFocused());
                    $em->persist($notification);
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', sprintf('You have been subscribed to %d job alert%s.'
                    , count($jobNotificationForm->getIndustries())
                    , count($jobNotificationForm->getIndustries()) == 1 ? '' : 's'
                ));
            }

            return $this->redirect($this->generateUrl('JobSeekerFolder'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/job-seeker/job-notification-list", name="JobNotificationList")
     * @Template()
     */
    public function jobNotificationListAction()
    {
        /** @var Resume $resume */
        $resume = $this->getUser();
        $notifications = $this->get('journalismjobs.manager.notification')->getActiveNotificationsForResume($resume);

        if (count($notifications) == 0) {
            $this->get('session')->getFlashBag()->add('error', 'You have 0 job alerts.');

            return $this->redirect($this->generateUrl('JobNotification'));
        }

        $choices = array();
        foreach ($notifications as $notification) {
            $choices[$notification->getId()] = 1;
        }
        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->generateUrl('JobNotificationList'),
            ))
            ->add('notifications', 'choice', array(
                'choices' => $choices,
                'expanded' => true,
                'multiple' => true,
                'error_bubbling' => true,
                'constraints' => array(
                    new Count(array(
                        'min' => 1,
                        'minMessage' => 'You must select at least 1 job alert to delete.',
                    )),
                ),
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $data = $form->getData();
            $notificationIds = $data['notifications'];
            $notificationManager = $this->get('journalismjobs.manager.notification');
            foreach ($notifications as $notification) {
                if (in_array($notification->getId(), $notificationIds)) {
                    $notificationManager->remove($notification);
                }
            }
            $notificationManager->flush();
            $this->get('session')->getFlashBag()->add('notice', sprintf('%d notification%s been deleted.'
                , count($notificationIds)
                , count($notificationIds) == 1 ? ' has' : 's have'
            ));

            return $this->redirect($this->generateUrl('JobSeekerFolder'));
        }

        return array(
            'resume' => $resume,
            'notifications' => $notifications,
            'form' => $form->createView(),
        );
    }
}
