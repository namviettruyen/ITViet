<?php

namespace ITViet\SiteBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use ITViet\SiteBundle\Entity\Member;
use ITViet\SiteBundle\Entity\MemberLoginInfo;

class LoadMemberData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $em)
    {
        $users = array();
        for($i=1; $i<=20; $i++) {
            $member = new Member();
            $member->setEmail("member{$i}@itviet.vn");
            $member->setFullName("Nguyễn Thị Hà {$i}");
            $member->setGender(mt_rand(0,1) == 0 ? 'M' : 'F');
            $member->setBirthDay(mt_rand(1,28));
            $member->setBirthMonth(mt_rand(1,12));
            $member->setBirthYear(mt_rand(1970, 1995));
            $member->setAddress('Pleiku Gialai');
            $member->setEnabled(true);
            $member->setPassword('123456');

            $logininfo = new MemberLoginInfo();
            $logininfo->setCount(0);

            $member->setLoginInfo($logininfo);

            $em->persist($logininfo);
            $em->persist($member);
            $em->flush();
        }
    }

    public function getOrder() {
        return 1;
    }
}
