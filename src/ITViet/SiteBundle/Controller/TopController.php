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
        $em = $this->get('doctrine.orm.entity_manager');
        $articles = $em->getRepository('ITVietSiteBundle:Article')->getArticlesActive();
        return array('articles' => $articles);
    }
}
