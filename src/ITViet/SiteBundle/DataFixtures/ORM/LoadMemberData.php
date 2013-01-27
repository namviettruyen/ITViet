<?php

namespace ITViet\SiteBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use ITViet\SiteBundle\Entity\Member;
use ITViet\SiteBundle\Entity\MemberLoginInfo;
use ITViet\SiteBundle\Entity\Category;

class LoadMemberData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    protected $container;
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    public function load(ObjectManager $em)
    {
        ///////////////////////////
        //member
        ///////////////////////////

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

        ///////////////////////////
        //category
        ///////////////////////////

        $arrCategories = array(
          array('Công nghệ phần mềm', true),
          array('Mạng máy tính', true),
          array('Đồ họa', true),
          array('Học tập', false),
        );

        $urlSlugger = $this->container->get('it_viet_site.url_slugger');
        $slugParams = array('toascii'=>true, 'tolower'=>true);

        foreach($arrCategories as $n => $category) {
            list($name, $isActive) = $category;

            $category = new Category();
            $category->setName($name);
            $category->setUrlPart($urlSlugger->slug($name, $slugParams));
            $category->setIsActive($isActive);

            $em->persist($category);
            $em->flush();
        }

    }

    public function getOrder() {
        return 1;
    }
}
