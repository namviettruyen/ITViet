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
        $isMember = $this->get('security.context')->isGranted('ROLE_MEMBER') ? true : false ;

        $temArticle = $em->getRepository('ITVietSiteBundle:Article')->find($id);
        $isOwner = $member != 'anon.' ? $member->getId() == $temArticle->getMember()->getId(): false;
        //if isAdmin show
        //if isOwner check isDeleted and show
        if ($isOwner) {
            $article = $em->getRepository('ITVietSiteBundle:Article')->findOneAsOwner($id);
        } else {
            $article = $em->getRepository('ITVietSiteBundle:Article')->findOneAsGuest($id);
        }
        //else check isDeleted, isActive and show

        if (!$article) {
           throw $this->createNotFoundException('Unable to find Article entity');
        }

        $em->getRepository('ITVietSiteBundle:ArticleViewInfo')->incrCount($article);
        $em->flush();

        $comments = $em->getRepository('ITVietSiteBundle:Comment')->getCommentByArticle($article->getId());

        return array(
          'article' => $article,
          'comments' => $comments,
          'isMember' => $isMember,
        );
    }
}
