<?php
namespace ITViet\SiteBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    //List article are posted by Member not include: isDeleted = true
    public function getArticlesByMember($member_id) {
        return $this->getArticles($member_id, null, null, true, null);
    }

    //List article load not include: isActive = false, isDeleted = true
    public function getArticles($member_id = null, $max = null, $offset = null, $del = null, $act = null) {
        $qb = $this->createQueryBuilder('a');

        if ($del)
            $qb->where('a.isDeleted = :del')->setParameter('del', false);
        if ($act)
            $qb->andWhere('a.isActive = :act')->setParameter('act', true);
        if ($member_id)
            $qb->andWhere('a.member = :mem_id')->setParameter('mem_id', $member_id);
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
}
