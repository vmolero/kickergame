<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccessControllerTest extends WebTestCase
{
    /**
     * @codeCoverageIgnore
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        // $this->assertEquals('Login', $crawler->filter('input#_submit')->attr('value'));
    }
}
