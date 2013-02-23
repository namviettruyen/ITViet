<?php

namespace ITViet\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ITViet\SiteBundle\Model\Paginator;

class TopController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $articleRepos = $em->getRepository('ITVietSiteBundle:Article');
        $articles_per_page = $this->container->getParameter('max_articles_on_toppage');

        $articles = $articleRepos->getArticlesActive($articles_per_page, 0);
        return array(
          'articles' => $articles,
        );
    }

    /**
     * @Template()
     */
    public function newestAction($page) {
        $em = $this->get('doctrine.orm.entity_manager');
        $articleRepos = $em->getRepository('ITVietSiteBundle:Article');
        $articles_per_page = $this->container->getParameter('max_articles_on_toppage');
        $display = 5;
        $count = $articleRepos->getCount();
        $paginator = new Paginator('_articles_newest', array(), $count, $display, $articles_per_page, $page);

        $articles = $articleRepos->getArticlesActive($paginator->pageSize, $paginator->offset);
        return array(
          'articles' => $articles,
          'paginator' => $paginator,
        );
    }
}
