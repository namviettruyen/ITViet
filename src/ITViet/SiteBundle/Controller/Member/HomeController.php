<?php

namespace ITViet\SiteBundle\Controller\Member;

use ITViet\SiteBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends BaseController
{
     /**
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $member = $this->get('security.context')->getToken()->getUser();
        $articles = $em->getRepository('ITVietSiteBundle:Article')->getArticles($member->getId());
        return array(
          'member' => $member,
          'articles' => $articles,
        );
    }
}
