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
        $session = $this->get('session');
        $em = $this->get('doctrine.orm.entity_manager');
        $member = new Member();

        $form = $this->createForm(new MemberEditType(), $member);

        $res = $this->render('ITVietSiteBundle:Member\\Config:editInfo.html.twig', array(
          'form' => $form->createView(),
        ));
        $res->setSharedMaxAge(30);
        $res->setPublic();
        return $res;
    }

}
