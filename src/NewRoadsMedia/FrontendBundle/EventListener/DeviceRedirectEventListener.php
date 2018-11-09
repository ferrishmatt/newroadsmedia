<?php

namespace NewRoadsMedia\FrontendBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DeviceRedirectEventListener
{
    /** @var \Symfony\Component\Routing\RouterInterface */
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->isMethod('GET')) {
            return;
        }

        $redirect = $request->get('redirect');
        if (in_array($redirect, array('desktop', 'mobile', 'tablet'))) {
            $request->getSession()->set('device-redirect', $redirect);
            $parameters = $request->attributes->get('_route_params');
            unset($parameters['redirect']);
            $url = $this->router->generate($request->attributes->get('_route'), $parameters);

            $event->setResponse(new RedirectResponse($url));
        }
    }
}