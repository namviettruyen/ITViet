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
}
