<?php

namespace ITViet\SiteBundle\Controller\Member;

use ITViet\SiteBundle\Controller\BaseController;
use ITViet\SiteBundle\Form\Type\ArticleNewType;
use ITViet\SiteBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

class ArticleController extends BaseController
{
     /**
     * @Template()
     */
    public function newAction(Request $request)
    {
        $member = $this->get('security.context')->getToken()->getUser();
        if (!$member) {
            throw $this->createNotFoundException('You have to login first');
        }
        $t = $this->get('translator');
        $article = new Article();
        $form = $this->createForm(new ArticleNewType(), $article);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if (strlen(strip_tags($form['content']->getData())) < 10)
                $form->addError(new FormError($t->trans('Content must larger 10 character')));

            if ($form->isValid()) {
                $em = $this->get('doctrine.orm.entity_manager');
                $article->setMember($member);
                $em->persist($article);
                $em->flush();
                $this->get('session')->setFlash('success', $t->trans('Post new article success'));
                return $this->redirect($this->generateMlUrl('_member_home'));
            }
        }

        return array(
          'form' => $form->createView(),
        );
    }


     /**
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $member = $this->get('security.context')->getToken()->getUser();
        if (!$member) {
            throw $this->createNotFoundException('You have to login first');
        }
        $t = $this->get('translator');

        //only article belong to member
        $article = $em->getRepository('ITVietSiteBundle:Article')->findOne($id, $member->getId());

        if (!$article) {
            throw $this->createNotFoundException('Article is not valid');
        }

        $form = $this->createForm(new ArticleNewType(), $article);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if (strlen(strip_tags($form['content']->getData())) < 10)
                $form->addError(new FormError($t->trans('Content must larger 10 character')));

            if ($form->isValid()) {
                $em->persist($article);
                $em->flush();
                $this->get('session')->setFlash('success', $t->trans('Edit article success'));
                return $this->redirect($this->generateMlUrl('_member_home'));
            }
        }

        return array(
          'form' => $form->createView(),
          'article' => $article,
        );
    }

    public function manageAction() {
        $member = $this->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');
        $t = $this->get('translator');
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $ids = $request->request->get('ckSelect');
            foreach($ids as $id) {
                $article = $em->getRepository('ITVietSiteBundle:Article')->find($id);
                if ($article) {
                    if (!$article->getMember()->equals($member))
                        return $this->redirect($this->generateMLUrl('_member_home'));
                    if ($request->request->get('unactive')) {
                        $article->setIsActive(false);
                        $em->persist($article);
                        $this->get('session')->setFlash('success', $t->trans('%count% article(s) has been updated', array("%count%" => count($ids))));

                    }

                    if ($request->request->get('active')) {
                        $article->setIsActive(true);
                        $em->persist($article);
                        $this->get('session')->setFlash('success', $t->trans('%count% article(s) has been updated', array("%count%" => count($ids))));

                    }

                    if ($request->request->get('delete')) {
                        $article->setIsDeleted(true);
                        $em->persist($article);
                        $this->get('session')->setFlash('success', $t->trans('%count% article(s) has been updated', array("%count%" => count($ids))));
                    }

                }
            }
        }
        $em->flush();
        return $this->redirect($this->generateMLUrl('_member_home'));
    }

}
