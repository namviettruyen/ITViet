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

        $results = $q->getResult();

        foreach($results as $result) {
            $replies = $this->getReplyByComment($result->getId());
            $result->replies = $replies;
        }

        return $results;
    }

    public function findOneById($id) {
         $q = $this->_em->createQuery("
            SELECT c
            FROM ITVietSiteBundle:Comment c
            WHERE c.isActive = ?1
            AND c.id = ?2
            ")->setParameter(1, true)
            ->setParameter(2, $id);

         try {
            $obj = $q->getSingleResult();
         } catch (\Doctrine\ORM\NoResultException $e) {
            $obj = null;
         }
        return $obj;
    }

    public function getReplyByComment($comment_id) {
         $q = $this->_em->createQuery("
            SELECT c
            FROM ITVietSiteBundle:Comment c
            WHERE c.isActive = ?1
            AND c.parentId = ?2
            ")->setParameter(1, true)
            ->setParameter(2, $comment_id);

        return $q->getResult();
    }
}
