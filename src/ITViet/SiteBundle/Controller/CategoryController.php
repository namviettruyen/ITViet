<?php

namespace ITViet\SiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ITViet\SiteBundle\Model\Paginator;

class CategoryController extends Controller
{
    /**
     * @Template()
     */
    public function showAction($urlPart, $page) {
        $em = $this->get('doctrine.orm.entity_manager');
        $category = $em->getRepository('ITVietSiteBundle:Category')->findOneBy(array('urlPart' => $urlPart));
        if (!$category)
           throw $this->createNotFoundException('Unable to find Category entity');

        $articleRepos = $em->getRepository('ITVietSiteBundle:Article');
        $articles_per_page = $this->container->getParameter('max_articles_on_toppage');
        $display = 5;
        $count = $articleRepos->getCount($category->getId());
        $paginator = new Paginator('_category_show', array('urlPart' => $category->getUrlPart()), $count, $display, $articles_per_page, $page);
        $articles = $articleRepos->getArticlesByCategory($category->getId(), $paginator->pageSize, $paginator->offset);
        return array(
          'category' => $category,
          'articles' => $articles,
          'paginator' => $paginator,
        );
    }
}
