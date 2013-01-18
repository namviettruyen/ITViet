<?php

namespace ITViet\SiteBundle\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Security\Core\Exception\DisabledException;


abstract class AuthenticationHandler
implements AuthenticationSuccessHandlerInterface,
           AuthenticationFailureHandlerInterface,
           LogoutSuccessHandlerInterface
{
    protected $router;
    protected $translator;

    public function __construct($router, $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }
    public function onLogoutSuccess(Request $request)
    {
        $this->resetLocale($request);
        $url = $this->generateMlUrl($request, '_homepage', true);

        $response = new RedirectResponse($url, 302);

        return $response;
    }
    //TODO: create "mlrouter" service instead
    public function generateMlUrl($request, $route, $absolute = false, $params = array())
    {
        if (null === $this->router) {
            throw new \LogicException('You must provide a RouterInterface instance to be able to use routes.');
        }

        if ($locale = $this->getLocale($request)) {
            try {
                return $this->router->generate('_'.$locale.$route, $params, $absolute);
            } catch (RouteNotFoundException $e) {}
        }
        return $this->router->generate($route, $params, $absolute);
    }

    protected function getLocale(Request $request)
    {
        if ($locale = $request->get('_locale')) {
            return $locale;
        }

        $context = $this->router->getContext();
        if ($locale = $context->getParameter('_locale')) {
            return $locale;
        }

        try {
            $parameters = $this->router->match($request->getPathInfo());

            if (isset($parameters['_locale'])) {
                return $parameters['_locale'];
            } elseif ($session = $request->getSession()) {
                return $session->getLocale();
            }
        } catch (\Exception $e) {
            return 'vi';
        }

        return null;
    }

    protected function resetLocale(Request $request)
    {
        $context = $this->router->getContext();
        if ($context->getParameter('_locale')) {
            return;
        }

        try {
            $parameters = $this->router->match($request->getPathInfo());

            if (isset($parameters['_locale'])) {
                $context->setParameter('_locale', $parameters['_locale']);
            } elseif ($session = $request->getSession()) {
                $context->setParameter('_locale', $session->getLocale());
            }
        } catch (\Exception $e) {
            // let's hope user doesn't use the locale in the path
        }
    }
}

