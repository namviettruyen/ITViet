<?php

namespace ITViet\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TopController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'HelloWorld');
    }
}
