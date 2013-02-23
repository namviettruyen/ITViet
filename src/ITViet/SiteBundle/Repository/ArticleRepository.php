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
    public function getArticlesByMember($member_id) {
        return $this->getArticles(null, $member_id, null, null, true, null);
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

    public function findOne($id, $member_id) {
        $qb = $this->createQueryBuilder('a')
          ->where('a.id = ?1')
          ->setParameter(1, $id)
          ->andWhere('a.isDeleted = ?2')
          ->setParameter(2, false);

        if (!$member_id)
            $qb->andWhere('a.isActive = ?3')->setParameter(3, true);
        else
            $qb->andWhere('a.member = ?3')->setParameter(3, $member_id);

        try {
            $article = $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $article = null;
        }

        return $article;
    }

    public function getCount($category_id = null) {
        $q = $this->_em->createQuery("
            SELECT count(a.id)
            FROM ITVietSiteBundle:Article a
            WHERE a.isActive = ?1
            ".($category_id?" AND a.category = $category_id ":"")."
          ")->setParameter(1, true);

        return $q->getSingleScalarResult();
    }
}
