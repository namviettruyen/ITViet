<?php

namespace ITViet\SiteBundle\Tests\Controller\Member;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testArticleForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/thanh-vien/bai-viet/moi');
#        $this->assertEquals('ITViet\SiteBundle\Controller\Member\\ArticleController::newAction', $client->getRequest()->attributes->get('_controller'));
#        $this->assertTrue($crawler->filter('html:contains("Login")')->count() > 0);
    }
}

