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
        $member = $this->get('security.context')->getToken()->getUser();

        if ($member != 'anon.')
          $member_id = $member->getId();
        else
          $member_id = null;

        $article = $em->getRepository('ITVietSiteBundle:Article')->findOne($id, $member_id);

        if (!$article) {
           throw $this->createNotFoundException('Unable to find Article entity');
        }
        return array('article' => $article);
    }
}
