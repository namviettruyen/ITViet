<?php

namespace ITViet\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Conponent\Routing\Exception\RouteNotFoundException;

class BaseController extends Controller
{
    protected function generateMlUrl($name, $parameters = array(), $absolute = false) {
        $router = $this->get('router');
        $session = $this->get('session');
        if ($locale = $session->getLocale()) {
            try {
                return $this->generateUrl('_'.$locale.$name, $parameters, $absolute);
            } catch (RouteNotFoundException $e) {}
        }
        return $this->generateUrl($name, $parameters, $absolute);
    }
}
