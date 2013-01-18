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
        //$memberService = $this->get('it_viet_site.member_service');
        return array(
          'hello' => 'hello',
        );
    }
}
