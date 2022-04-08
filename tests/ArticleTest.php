<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleTest extends WebTestCase
{
    private $crawler;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/article');
    }

    public function testBaseArticle(): void
    {
        $h2 = $this->crawler->filter('main > header > h2')->first();

        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains($h2->nodeName(), 'Liste des articles');
        $this->assertEquals( 'Liste des articles', $h2->text());
    }

    public function testPageAjouter(): void
    {
        $link = $this->crawler->filter('a:contains("Ajouter")')->link();
        $this->client->click($link);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('main > header > h2', "Editer un article");
    }

    public function testAjouterArticle(): void
    {
        /*$link = $this->crawler->filter('a:contains("Ajouter")')->link();
        $this->client->click($link);*/

        $this->client->clickLink("Ajouter");

        $this->assertResponseIsSuccessful();

        $this->client->followRedirects();
        $crawler = $this->client->submitForm('Enregistrer', [
            'post[title]' => 'Article test #1',
            'post[tags]' => 'Article, test, WebTestCase'
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('main > header > h2', "Liste des articles");

        $tr = $crawler->filter('table > tbody > tr')->last();
        $titre = $tr->filter('td:nth-child(3)')->text();
        $this->assertEquals('Article test #1', $titre);
    }
}
