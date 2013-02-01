<?php
namespace ITViet\SiteBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getArticles($member_id = null, $max = null, $offset = null) {

        $qb = $this->createQueryBuilder('a')
          ->where('a.isDeleted = :del')
          ->setParameter('del', false);

        if ($member_id)
            $qb->andWhere('a.member = :mem_id')->setParameter('mem_id', $member_id);
        if ($max)
            $qb->setMaxResults($max);
        if ($offset)
            $qb->setFirstResult($offset);

        $qb->orderBy('a.postedAt', 'DESC');
        return $qb->getQuery()->getResult();
    }
}
