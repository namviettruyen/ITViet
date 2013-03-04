<?php
namespace ITViet\SiteBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{

    //show public
    public function getArticlesActive($max, $offset) {
        return $this->getArticles(null, null, $max, $offset, true, true);
    }

    //show for member only
    public function getArticlesByMember($member_id, $max, $offset) {
        return $this->getArticles(null, $member_id, $max, $offset, true, null);
    }

    //show public
    public function getArticlesByCategory($category_id, $max, $offset) {
        return $this->getArticles($category_id, null, $max, $offset, true, true);
    }

    public function getArticles($category_id = null, $member_id = null, $max = null, $offset = null, $del = null, $act = null) {
        $qb = $this->createQueryBuilder('a');

        if ($del)
            $qb->where('a.isDeleted = :del')->setParameter('del', false);
        if ($act)
            $qb->andWhere('a.isActive = :act')->setParameter('act', true);
        if ($member_id)
            $qb->andWhere('a.member = :mem_id')->setParameter('mem_id', $member_id);
        if ($category_id)
            $qb->andWhere('a.category = :cate_id')->setParameter('cate_id', $category_id);

        if ($max)
            $qb->setMaxResults($max);
        if ($offset)
            $qb->setFirstResult($offset);

        $qb->orderBy('a.postedAt', 'DESC');
        return $qb->getQuery()->getResult();
    }

    public function findOneAsOwner($id) {
        return $this->findOne($id, true);
    }
    public function findOneAsGuest($id) {
        return $this->findOne($id, true, true);
    }
    public function findOne($id, $del = null, $act = null) {
        $qb = $this->createQueryBuilder('a')
          ->where('a.id = ?1')
          ->setParameter(1, $id);
        if ($del)
          $qb->andWhere('a.isDeleted = ?2')
          ->setParameter(2, false);
        if ($act)
          $qb->andWhere('a.isActive = ?3')
          ->setParameter(3, true);

        try {
            $article = $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $article = null;
        }

        return $article;
    }

    public function getCountByCategory($category_id) {
        return $this->getCount($category_id);
    }

    public function getCountByMember($member_id) {
        return $this->getCount(null, $member_id);
    }

    public function getCount($category_id = null, $member_id = null) {
        $q = $this->_em->createQuery("
            SELECT count(a.id)
            FROM ITVietSiteBundle:Article a
            WHERE a.isActive = ?1
            ".($category_id?" AND a.category = $category_id ":"")."
            ".($member_id?" AND a.member = $member_id ":"")."
          ")->setParameter(1, true);

        return $q->getSingleScalarResult();
    }
}
