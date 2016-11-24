<?php
namespace ItraBundle\Services;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectUserListener
{
    private $route;

    public function __construct(RouterInterface $router)
    {
        $this->route = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        $exception = $event->getException();
        if($exception->getStatusCode() == 403) {

            $response = new Response();
            $event->setResponse(new RedirectResponse($this->route->generate('homepage')));
        }
    }
}