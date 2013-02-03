<?php

namespace ITViet\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ArticleController extends Controller
{
    /**
     * @Template()
     */
    public function showAction($id) {
        $em = $this->get('doctrine.orm.entity_manager');
        $article = $em->getRepository('ITVietSiteBundle:Article')->findOne($id);

        if (!$article) {
           throw $this->createNotFoundException('Unable to find Article entity');
        }
        return array('article' => $article);
    }
}
