<?php
namespace ITViet\SiteBundle\Repository;
use Doctrine\ORM\EntityRepository;

class BaseLogRepository extends EntityRepository
{
    public function isNewAddr($articleViewInfo, $ipAddr) {
        $q = $this->_em->createQuery("
          SELECT count(b.id)
          FROM ITVietSiteBundle:BaseLog b
          WHERE b.articleViewInfo = ?1
          AND b.ipAddr = ?2
          ")->setParameter(1, $articleViewInfo->getId())
          ->setParameter(2, $ipAddr);
        if ($q->getSingleScalarResult() == 0)
            return true;
        else
            return false;
    }
}
