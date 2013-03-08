<?php
namespace ITViet\SiteBundle\Repository;
use Doctrine\ORM\EntityRepository;
use ITViet\SiteBundle\Entity\Article;

class ArticleViewInfoRepository extends EntityRepository
{
    public function incrCount(Article $article) {
        $conn = $this->_em->getConnection();
        $conn->executeUpdate('
          UPDATE ArticleViewInfo avi
          LEFT JOIN Article a ON avi.id = a.article_view_info_id
          SET avi.count = avi.count + 1
          WHERE a.id = ?
          ', array($article->getId()));
    }
}
