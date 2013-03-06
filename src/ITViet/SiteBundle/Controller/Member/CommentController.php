<?php
namespace ITViet\SiteBundle\Controller\Member;
use ITViet\SiteBundle\Controller\BaseController;
use ITViet\SiteBundle\Entity\Comment;
use ITViet\SiteBundle\Entity\Article;

class CommentController extends BaseController
{
    public function createAction($article_id) {
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->get('request');
        $t = $this->get('translator');
        $member = $this->get('security.context')->getToken()->getUser();
        $article = $em->getRepository('ITVietSiteBundle:Article')->findOneAsGuest($article_id);

        if (!$member) {
            throw $this->createNotFoundException('You have to login first');
        }

        if ($request->getMethod() == 'POST') {
            $content = $request->request->get('txtCmt');

            $comment = new Comment();
            $comment->setContent($content);
            $comment->setMember($member);
            $comment->setArticle($article);
            $em->persist($comment);
            $em->flush();
            $this->get('session')->setFlash('success', $t->trans('Post new comment success'));
            return $this->redirect($this->generateMlUrl('_article_show',array(
              'id'=> $article->getId(),
              'category'=> $article->getCategory()->getUrlPart(),
              'urlPart'=> $article->getUrlPart() 
            )));
        }
    }
}
