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
            AND c.parentId = ?2
            AND c.article = ?3
            ")->setParameter(1, true)
            ->setParameter(2, 0)
            ->setParameter(3, $article_id);

        return $q->getResult();
    }
}
