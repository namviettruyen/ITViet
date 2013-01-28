<?php

namespace ITViet\SiteBundle\Controller\Member;

use ITViet\SiteBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PublicController extends BaseController
{
     /**
     * @Template()
     */
    public function showAction($id) {
        $em = $this->get('doctrine.orm.entity_manager');
        $member = $em->getRepository('ITVietSiteBundle:Member')->find($id);

        if (!$member) {
            throw $this->createNotFoundException('Unable to find data');
        }

        return array(
          'member' => $member,
        );
    }
}
