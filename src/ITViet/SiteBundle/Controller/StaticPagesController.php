<?php

namespace ITViet\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StaticPagesController extends Controller
{
    /**
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function contactAction()
    {
        return array();
    }

}
