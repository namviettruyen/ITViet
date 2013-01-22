<?php

namespace ITViet\SiteBundle\Controller\Member;

use ITViet\SiteBundle\Controller\BaseController;
use ITViet\SiteBundle\Entity\Member;
use ITViet\SiteBundle\Form\Type\MemberEditType;
use Symfony\Component\Form\FormError;

class ConfigController extends BaseController
{
    public function editInfoAction() {
        $t = $this->get('translator');
        $request = $this->getRequest();
        $em = $this->get('doctrine.orm.entity_manager');
        $member = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new MemberEditType(), $member);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em->persist($member);
                $em->flush();
                $this->get('session')->setFlash('success', $t->trans('Update success'));
                return $this->redirect($this->generateMlUrl('_member_home'));
            }
        }

        $res = $this->render('ITVietSiteBundle:Member\\Config:editInfo.html.twig', array(
          'form' => $form->createView(),
        ));
        $res->setSharedMaxAge(30);
        $res->setPublic();
        return $res;
    }
}
