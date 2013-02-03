<?php

namespace ITViet\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LayoutController extends Controller
{
    public function leftSideBarAction($active) {
        $em = $this->get('doctrine.orm.entity_manager');
        $categories = $em->getRepository('ITVietSiteBundle:Category')->findAll();
        $res = $this->render('ITVietSiteBundle:Layout:leftSideBar.html.twig', array(
          'active' => $active,
          'categories' => $categories,
        ));
        $res->setSharedMaxAge(300);
        $res->setPublic();
        return $res;
      }
}
