<?php

namespace ITViet\SiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CategoryController extends Controller
{
    /**
     * @Template()
     */
    public function showAction($urlPart) {
        $em = $this->get('doctrine.orm.entity_manager');
        $category = $em->getRepository('ITVietSiteBundle:Category')->findOneBy(array('urlPart' => $urlPart));
        if (!$category)
           throw $this->createNotFoundException('Unable to find Category entity');

        $articles = $em->getRepository('ITVietSiteBundle:Article')->getArticlesByCategory($category->getId());
        return array(
          'category' => $category,
          'articles' => $articles,
        );
    }
}
