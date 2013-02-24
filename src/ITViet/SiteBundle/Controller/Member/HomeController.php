<?php

namespace ITViet\SiteBundle\Controller\Member;

use ITViet\SiteBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ITViet\SiteBundle\Model\Paginator;

class HomeController extends BaseController
{
     /**
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->getRequest();
        $member = $this->get('security.context')->getToken()->getUser();
        if (!$member)
            throw $this->createNotFoundException('Unable to find Member entity');

#        $articleRepos = $em->getRepository('ITVietSiteBundle:Article');
#        $count = $articleRepos->getCountByMember($member->getId());
#        $page = $request->get('page');
#        $paginator = new Paginator(null, array(), $count, 5, 1, $page);
#        $articles = $articleRepos->getArticlesByMember($member->getId(), $paginator->pageSize, $paginator->offset);
        return array(
          'member' => $member,
#          'articles' => $articles,
#          'paginator' => $paginator,
        );
    }
}
