<?php
namespace ITViet\SiteBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getArticles($member_id = null, $max = null, $offset = null) {
        $q = $this->_em->createQuery('
          SELECT a FROM ITVietSiteBundle:Article a
          WHERE a.isDeleted = ?1
          AND '.($member_id ? 'a.member = ?2' : '').'
          ORDER BY a.postedAt DESC
          ')->setParameter(1, false);

        if ($member_id) $q->setParameter(2, $member_id);
        if ($max) $q->setMaxResults($max);
        if ($offset) $q->setFirstResult($offset);

        return $q->getResult();
    }
}
