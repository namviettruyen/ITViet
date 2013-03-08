<?php
namespace ITViet\SiteBundle\Service;
use ITViet\SiteBundle\Entity\BaseLog;

class LoggerService
{
    private $request;
    private $serviceContainer;
    private $em;

    public function __construct($serviceContainer, $em) {
        $this->serviceContainer = $serviceContainer;
        $this->em = $em;
    }

    private function getClientIp() {
        if ($request = $this->serviceContainer->get('request'))
            return $request->getClientIp(true);
        else
            return null;
    }

    private function getBaseLogRepos() {
        return $this->em->getRepository('ITVietSiteBundle:BaseLog');
    }

    public function checkUserPresent($articleViewInfo) {
        $isValid = $this->getBaseLogRepos()->isNewAddr($articleViewInfo, $this->getClientIp());
        if ($isValid) {
            $log = new BaseLog();
            $log->setArticleViewInfo($articleViewInfo);
            $log->setIpAddr($this->getClientIp());
            $this->em->persist($log);
            $this->em->flush();

            return true;
        }
        return false;
    }
}
