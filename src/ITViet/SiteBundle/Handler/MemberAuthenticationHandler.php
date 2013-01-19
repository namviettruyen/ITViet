<?php

namespace ITViet\SiteBundle\Handler;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\Exception\DisabledException;


class MemberAuthenticationHandler extends AuthenticationHandler
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $this->resetLocale($request);

        if ($request->isXmlHttpRequest()) {
#            $url = $this->generateMlUrl($request, '_homepage', true);
#            $response = new Response(json_encode(array(
#              'authStatus' => 'success',
#              'redirectTo' => $url,
#            )));
#            $response->headers->set('Content-Type', 'application/json');
        } else {
            $session = $request->getSession();
            if ($url = $session->get('_security.target_path')) {
                $session->remove('_security.target_path');
            } else {
                $url = $this->generateMlUrl($request, '_member_home', true);
            }

            $request->getSession()->setFlash('success', 'Login successfully');

            $response = new RedirectResponse($url, 302);
        }

        return $response;
    }

    #Handling when login wrong information, when access invalid config security handle
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $jumpTo = null;

        if ($request->isXmlHttpRequest()) {
#            $request->getSession()->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
#
#            $errorMsg = $exception->getMessage();
#            if ($this->translator) {
#                if ($exception instanceof DisabledException) {
#                    $this->resetLocale($request);
#                    $user = $exception->getExtraInformation();
#                    $href = $this->generateMlUrl($request, '_jobseeker_resend_confirmation', true, array('email' => $user->getEmail()));
#                    $errorMsg = $this->translator->trans($errorMsg, array(
#                      '%href%' => $href,
#                    ));
#                    $jumpTo = $href;
#                } else {
#                    $errorMsg = $this->translator->trans($errorMsg);
#                }
#            }
#            $response = new Response(json_encode(array(
#              'authStatus' => 'failure',
#              'errorMsg' => $errorMsg,
#              'jumpTo' => $jumpTo,
#            )));
#            $response->headers->set('Content-Type', 'application/json');
        } else {
            $request->getSession()->set(SecurityContextInterface::AUTHENTICATION_ERROR, $exception);
            $this->resetLocale($request);
            $url = $this->generateMlUrl($request, '_member_login', true);
            $response = new RedirectResponse($url, 302);
        }

        return $response;
    }
/*
    public function onLogoutSuccess(Request $request)
    {
        $this->resetLocale($request);
        $url = $this->generateMlUrl($request, '_homepage', true);

        $response = new RedirectResponse($url, 302);

        $session = $request->getSession();
        $session->setFlash('success', 'Logout successfully');

        return $response;
    }
 */
}
