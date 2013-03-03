<?php
namespace ITViet\SiteBundle\Repository;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function getCommentByArticle($article_id) {
        $q = $this->_em->createQuery("
            SELECT c
            FROM ITVietSiteBundle:Comment c
            WHERE c.isActive = ?1
            AND c.article = $article_id
          ")->setParameter(1, true);

        return $q->getResult();
    }
}
