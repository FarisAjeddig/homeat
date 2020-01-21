<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MealControllerTest extends WebTestCase
{
    public function testCreatemeal()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/create');
    }

    public function testResearchmeals()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/research');
    }

    public function testEditmeal()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/edit');
    }

    public function testShowmeal()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/show');
    }

    public function testOrdermeal()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/order');
    }

    public function testDeletemeal()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/delete');
    }

}
