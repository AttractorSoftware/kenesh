<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase {

    private $entityManager;

    public function __construct() {
        $client = static::createClient();
        $this->entityManager = $client->getContainer()->get('doctrine')->getManager();
    }

    public function testLoadFactions() {
        $client = static::createClient();

        $client->request(
            'POST',
            '/load/factions',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '[{"title":"Faction 1"}, {"title": "Faction 2"}]'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $jsonData = json_decode($response->getContent());

        $faction = $this->entityManager->getRepository('AdminBundle:Faction')->findOneBy(array('title'=>'Faction 1'));

        $this->assertEquals(true, is_object($faction));
        $this->assertEquals('ok', $jsonData->status);
    }

    public function testLoadDeputies() {
        $client = static::createClient();

        $client->request(
            'POST', '/load/deputies', array(), array(),
            array('CONTENT_TYPE' => 'application/json'),
            '[{
                "full_name": "Deputy 1",
                "faction": "Faction 1",
                "bio": "Bio",
                "photo_url": "http://kenesh.kg/photo.png",
                "kenesh_url": "http://kenesh.kg/page"
            }]'
        );

        $response = $client->getResponse();

        //die(print_r($response->getContent()));

        $this->assertEquals(200, $response->getStatusCode());
        $jsonData = json_decode($response->getContent());

        $deputy = $this->entityManager
            ->getRepository('AdminBundle:Deputy')
            ->findOneBy(array('fullName'=>'Deputy 1'));

        $this->assertEquals(true, is_object($deputy));
        $this->assertEquals('ok', $jsonData->status);
    }
}
