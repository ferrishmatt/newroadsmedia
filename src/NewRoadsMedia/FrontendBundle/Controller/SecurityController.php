<?php

namespace NewRoadsMedia\FrontendBundle\Controller;

use NewRoadsMedia\FrontendBundle\Entity\Employer;
use NewRoadsMedia\FrontendBundle\Form\Type\CreateEmployerAccountFormType;
use NewRoadsMedia\FrontendBundle\Form\Type\CreateJobSeekerAccountFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Gregwar\CaptchaBundle\Type\CaptchaType;

class SecurityController extends Controller
{
    /**
     * @Route("/login_job_seeker",       name="LoginJobSeeker",       defaults={"login": "JOB_SEEKER"})
     * @Route("/login_post_resume",      name="LoginPostResume",      defaults={"login": "POST_RESUME"})
     * @Route("/login_job_basket",       name="LoginJobBasket",       defaults={"login": "JOB_BASKET"})
     * @Route("/login_job_notification", name="LoginJobNotification", defaults={"login": "JOB_NOTIFICATION"})
     * @Route("/login_job_packages",     name="LoginJobPackages",     defaults={"login": "JOB_PACKAGES"})
     * @Route("/login_employer",         name="LoginEmployer",        defaults={"login": "EMPLOYERS"})
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        switch ($request->get('login')) {
            case 'EMPLOYERS':
                $action = $this->generateUrl('LoginCheckEmployers');
                $template = 'NewRoadsMediaFrontendBundle:Security:loginEmployers.html.twig';
                $createAccountRedirect = $this->generateUrl('EmployerManager');
                $createAccountAction = $this->generateUrl('LoginEmployer');
                $createAccountType = 'EMPLOYER';
                break;
            case 'POST_RESUME':
                $action = $this->generateUrl('LoginCheckPostResume');
                $template = 'NewRoadsMediaFrontendBundle:Security:loginPostResume.html.twig';
                $createAccountRedirect = $this->generateUrl('UploadProfile');
                $createAccountAction = $this->generateUrl('LoginPostResume');
                $createAccountType = 'JOB_SEEKER';
                break;
            case 'JOB_PACKAGES':
                $action = $this->generateUrl('LoginCheckJobPackages');
                $template = 'NewRoadsMediaFrontendBundle:Security:loginJobPackages.html.twig';
                $createAccountRedirect = $session->get('_security.job_packages.target_path', $this->generateUrl('MultipleJobAds'));
                $createAccountAction = $this->generateUrl('LoginJobPackages');
                $createAccountType = 'EMPLOYER';
                break;
            case 'JOB_NOTIFICATION':
                $action = $this->generateUrl('LoginCheckJobNotification');
                $template = 'NewRoadsMediaFrontendBundle:Security:loginJobNotification.html.twig';
                $createAccountRedirect = $this->generateUrl('JobNotification');
                $createAccountAction = $this->generateUrl('LoginJobNotification');
                $createAccountType = 'JOB_SEEKER';
                break;
            case 'JOB_BASKET':
                $action = $this->generateUrl('LoginCheckJobBasket');
                $template = 'NewRoadsMediaFrontendBundle:Security:loginJobBasket.html.twig';
                $createAccountRedirect = $this->get('session')->get('_security.job_basket.target_path', $this->generateUrl('JobSeekerFolder'));
                $createAccountAction = $this->generateUrl('LoginJobBasket');
                $createAccountType = 'JOB_SEEKER';
                break;
            case 'JOB_SEEKER':
            default:
                $action = $this->generateUrl('LoginCheckJobSeeker');
                $template = 'NewRoadsMediaFrontendBundle:Security:loginJobSeeker.html.twig';
                $createAccountRedirect = $this->generateUrl('JobSeekerFolder');
                $createAccountAction = $this->generateUrl('LoginJobSeeker');
                $createAccountType = 'JOB_SEEKER';
                break;
        }

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        $form = $this->get('form.factory')->createNamedBuilder('', 'form', null, array(
                'attr' => array('novalidate' => 'novalidate'),
                'method' => 'POST',
                'action' => $action,
            ))
            ->add('_username', 'email', array(
                'label' => 'Your email address',
                'data' => $lastUsername,
            ))
            ->add('_password', 'password', array(
                'label' => 'Password',
            ))
            ->getForm()
        ;

        if ($error) {
            $message = $this->get('translator')->trans($error->getMessage());
            $form->addError(new FormError($message));
        }

        if ($createAccountType == 'JOB_SEEKER') {
            $resumeManager = $this->get('journalismjobs.manager.resume');
            $accountForm = $this->createForm(new CreateJobSeekerAccountFormType($resumeManager), null, array(
                'attr' => array('novalidate' => 'novalidate'),
                'method' => 'POST',
                'action' => $createAccountAction,
            ));
            $accountForm->add('captcha', 'captcha', [
               'length' => 5,
               'attr' => ['maxlength' => 10, 'placeholder' => 'Security code'],
               'invalid_message' => 'Security code is invalid',
               'background_color' => [255, 255, 255],
               'height' => 30,
               'width' => 80,
               'error_bubbling' => true,
               'reload' => false
            ]);
            $accountForm->handleRequest($this->getRequest());
            if ($accountForm->isValid()) {
                $resume = $resumeManager->create();
                $resume->setEmail($accountForm->get('email')->getData());
                $resume->setPassword($accountForm->get('password')->getData());
                $resume->setName($accountForm->get('name')->getData());
                $resume->setDateEntered(new \DateTime('now'));
                $resumeManager->save($resume);
                $this->get('journalismjobs.frontend.user')->loginUser($resume, 'job_seekers');

                return $this->redirect($createAccountRedirect);
            }
        } else {
            $employerManager = $this->get('journalismjobs.manager.employer');
            $accountForm = $this->createForm(new CreateEmployerAccountFormType($employerManager), null, array(
                'attr' => array('novalidate' => 'novalidate'),
                'method' => 'POST',
                'action' => $createAccountAction,
            ));
            $accountForm->add('captcha', 'captcha', [
               'length' => 5,
               'attr' => ['maxlength' => 10, 'placeholder' => 'Security code'],
               'invalid_message' => 'Security code is invalid',
               'background_color' => [255, 255, 255],
               'height' => 30,
               'width' => 80,
               'error_bubbling' => true,
               'reload' => false
            ]);
            $accountForm->handleRequest($this->getRequest());
            if ($accountForm->isValid()) {
                /** @var Employer $employer */
                $employer = $accountForm->getData();
                $employerManager->save($employer);
                $this->get('journalismjobs.frontend.user')->loginUser($employer, 'employers');

                return $this->redirect($createAccountRedirect);
            }
        }

        return $this->render($template, array(
            'form' => $form->createView(),
            'accountForm' => $accountForm->createView(),
        ));
    }

    /**
     * @Route("/login", name="Login")
     * @Template()
     */
    public function generalLoginAction()
    {
        $form = $this->get('form.factory')->createNamedBuilder('', 'form', null, array(
                'method' => 'POST',
                'action' => '',
            ))
            ->add('_username', 'email', array(
                'label' => 'Your email address',
                'required' => true,
            ))
            ->add('_password', 'password', array(
                'label' => 'Password',
                'required' => true,
            ))
            ->add('_target_path', 'hidden')
            ->getForm()
        ;

        return array(
            'form' => $form->createView(),
            'loginJobSeeker' => $this->generateUrl('LoginCheckJobSeeker'),
            'loginEmployer' => $this->generateUrl('LoginCheckEmployers'),
            'targetJobSeeker' => 'JobSeekerFolder',
            'targetEmployer' => 'EmployerManager',
        );
    }

    /**
     * @Route("/job-seeker/login_check",                  name="LoginCheckJobSeeker")
     * @Route("/job-seeker/post-resume-login-check",      name="LoginCheckPostResume")
     * @Route("/job-seeker/job-basket-login-check",       name="LoginCheckJobBasket")
     * @Route("/job-seeker/job-notification-login-check", name="LoginCheckJobNotification")
     * @Route("/job-packages/login_check",                name="LoginCheckJobPackages")
     * @Route("/employers/login_check",                   name="LoginCheckEmployers")
     */
    public function loginCheckAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/job-seeker/logout", name="LogoutJobSeeker")
     * @Route("/employers/logout", name="LogoutEmployer")
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }


    /**
     * @Route("/reset-password/{token}", name="ResetPassword")
     * @Template()
     */
    public function resetPasswordAction($token)
    {
        $userForgotPasswordRequestManager = $this->get('journalismjobs.manager.user_forgot_password_request');
        $userForgotPasswordRequest = $userForgotPasswordRequestManager->getRequest($token);
        if (!$userForgotPasswordRequest) {
            throw new NotFoundHttpException(sprintf('User forgot password request token "%s" could not be found.', $token));
        }

        $now = new \DateTime('now');
        if ($userForgotPasswordRequest->getTimeExpires() < $now) {
            $this->get('session')->getFlashBag()->add('notice', 'That link has expired, please make a new request to reset your password.');

            return $this->redirect($this->generateUrl('ForgotPassword'));
        }

        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->generateUrl('ResetPassword', array('token' => $token)),
                'attr' => array('novalidate' => 'novalidate'),
            ))
            ->add('email', 'email', array(
                'label' => 'Email:',
                'constraints' => array(
                    new NotBlank(array('message' => 'You must specify your e-mail address.')),
                    new Email(array('message' => 'Email address is invalid.')),
                ),
                'error_bubbling' => true,
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'error_bubbling' => true,
                'invalid_message' => 'The new password fields do not match.',
                'constraints' => array(
                    new NotBlank(array('message' => 'Please enter your new password.')),
                    new Length(array(
                        'min' => 6,
                        'minMessage' => 'Your new password must be at least {{ limit }} characters long.',
                    )),
                ),
                'first_options' => array('label' => 'New Password:'),
                'second_options' => array('label' => 'Confirm New Password:'),
            ))
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($userForgotPasswordRequest) {
                $data = $event->getData();
                if (strtolower($data['email']) != strtolower($userForgotPasswordRequest->getEmail())) {
                    $event->getForm()->addError(new FormError('The email address you entered does not match the email address in our system.'));
                }
            })
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $email = $form->get('email')->getData();
            $resumeManager = $this->get('journalismjobs.manager.resume');
            /** @var UserInterface $user */
            $user = $resumeManager->findOneByEmail($email);
            $firewall = 'job_seekers';
            if (!$user) {
                $employerManager = $this->get('journalismjobs.manager.employer');
                $user= $employerManager->findOneByEmail($email);
                $firewall = 'employers';
            }
            if ($user) {
                // update password and remove user forgot password request
                $password = $form->get('password')->getData();
                $user->setPassword($password);
                $this->get('doctrine')->getManager()->persist($user);
                $userForgotPasswordRequestManager->remove($userForgotPasswordRequest);
                $this->get('doctrine')->getManager()->flush();

                // log in the user
                $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
                $this->get("security.context")->setToken($token); //now the user is logged in
                $request = $this->getRequest();
                $event = new InteractiveLoginEvent($request, $token);
                $this->get('event_dispatcher')->dispatch("security.interactive_login", $event); //now dispatch the login event

                $this->get('session')->getFlashBag()->add('notice', 'Your password has been successfully reset.');

                if ($firewall == 'job_seekers') {
                    return $this->redirect($this->generateUrl('JobSeekerFolder'));
                } else if ($firewall == 'employers') {
                    return $this->redirect($this->generateUrl('EmployerManager'));
                }
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/forgot-password", name="ForgotPassword")
     * @Template()
     */
    public function forgotPasswordAction()
    {
        $resumeManager = $this->get('journalismjobs.manager.resume');
        $employerManager = $this->get('journalismjobs.manager.employer');
        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->generateUrl('ForgotPassword'),
            ))
            ->add('email', 'email', array(
                'label' => false,
                'constraints' => array(
                    new NotBlank(array('message' => 'You must specify your e-mail address.')),
                    new Email(array('message' => 'Email address is invalid.')),
                ),
                'error_bubbling' => true,
            ))
            ->add('submit', 'submit', array(
                'label' => 'Send Email',
            ))
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($resumeManager, $employerManager) {
                $form = $event->getForm();
                if ($form->getErrors()) {
                    return;
                }

                $data = $event->getData();
                $email = $data['email'];
                $resume = $resumeManager->findOneByEmail($email);
                if (!$resume) {
                    $employer = $employerManager->findOneByEmail($email);
                    if (!$employer) {
                        $form->addError(new FormError('Sorry, the e-mail address you entered is not in our system. Please check your e-mail address.'));
                    }
                }
            })
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        if ($form->isValid() && $form->get('submit')->isClicked()) {
            $userForgotPasswordManager = $this->get('journalismjobs.manager.user_forgot_password_request');
            $userForgotPasswordManager->createUserForgotPassword($form->get('email')->getData());

            return $this->redirect($this->generateUrl('ForgotPasswordSentEmail'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/forgot-password/sent-email", name="ForgotPasswordSentEmail")
     * @Template()
     */
    public function forgotPasswordSentEmailAction()
    {
        return array();
    }

    /**
     * @Route("/user/delete-account", name="DeleteAccount")
     * @Template()
     */
    public function deleteAccountAction()
    {
        $user = $this->getUser();
        $form = $this->createFormBuilder(null, array(
                'method' => 'POST',
                'action' => $this->generateUrl('DeleteAccount'),
            ))
            ->add('delete', 'submit', array(
                'label' => 'Delete Job Seeker Folder',
            ))
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());
        $isDeleted = false;
        if ($form->isValid() && $form->get('delete')->isClicked()) {
            $this->get('doctrine')->getManager()->remove($user);
            $this->get('doctrine')->getManager()->flush();
            $this->get('security.context')->setToken(null);
            $this->get('session')->invalidate();
            $isDeleted = true;
        }

        return array(
            'user' => $user,
            'form' => $form->createView(),
            'isDeleted' => $isDeleted,
        );
    }

    /**
     * @Route("/user/change-password", name="ChangePassword")
     * @Template
     */
    public function changePasswordAction()
    {
        /** @var UserInterface $user */
        $user = $this->getUser();
        $fb = $this->createFormBuilder(null, array('attr' => array('novalidate' => 'novalidate')))
            ->add('currentEmail', 'email', array(
                'label' => 'Current E-mail:',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'You must enter your current email address.',
                    )),
                    new Email(array(
                        'message' => 'Invalid email address entered into the current email field.',
                    )),
                    new EqualTo(array(
                        'value' => $user->getUsername(),
                        'message' => 'The value entered into the current email field does not match your current email.',
                    ))
                ),
                'error_bubbling' => true,
            ))
            ->add('currentPassword', 'password', array(
                'label' => 'Current Password:',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'You must enter your current password.',
                    )),
                    new EqualTo(array(
                        'value' => $user->getPassword(),
                        'message' => 'The value entered into the current password field does not match your current password.',
                    ))
                ),
                'error_bubbling' => true,
            ))
            ->add('newEmail', 'email', array(
                'label' => 'New E-mail:',
                'constraints' => array(
                    new Email(array(
                        'message' => 'Invalid email address entered into the new email field.',
                    )),
                ),
                'error_bubbling' => true,
            ))
            ->add('newPassword', 'repeated', array(
                'first_options' => array('label' => 'New Password:'),
                'second_options' => array('label' => 'Confirm New Password:'),
                'type' => 'password',
                'error_bubbling' => true,
                'invalid_message' => 'The new password fields do not match.',
                'constraints' => array(
                    new Length(array(
                        'min' => 6,
                        'minMessage' => 'Your new password must be at least {{ limit }} characters long.',
                    )),
                ),
            ))
            ->add('newName', 'text', array(
                'label' => 'New Account Name:',
                'data' => $user->getName(),
            ))
        ;
        if ($user instanceof Employer) {
            $fb
                ->add('newCompany', 'text', array(
                    'label' => 'New Company Name:',
                    'data' => $user->getCompany(),
                ))
            ;
        }

        $form = $fb->getForm();
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $data = $form->getData();
            if (!empty($data['newEmail'])) {
                $user->setUsername($data['newEmail']);
            }
            if (!empty($data['newPassword'])) {
                $user->setPassword($data['newPassword']);
            }
            if ($form->has('newName') && !empty($data['newName'])) {
                $user->setName($data['newName']);
            }
            if ($form->has('newCompany') && !empty($data['newCompany'])) {
                $user->setCompany($data['newCompany']);
            }
            $this->get('doctrine')->getManager()->persist($user);
            $this->get('doctrine')->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Your login credentials have been updated.');

            if ($this->get('security.context')->isGranted('ROLE_JOB_SEEKER')) {
                return $this->redirect($this->generateUrl('JobSeekerFolder'));
            }

            if ($this->get('security.context')->isGranted('ROLE_EMPLOYER')) {
                return $this->redirect($this->generateUrl('EmployerManager'));
            }

            return $this->redirect($this->generateUrl('Index'));
        }

        return array(
            'form' => $form->createView(),
        );
    }
}