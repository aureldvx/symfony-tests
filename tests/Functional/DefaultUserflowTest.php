<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Helper\AbstractFunctionalTestCase;

class DefaultUserflowTest extends AbstractFunctionalTestCase
{
    public function testHomepageTitleIsVisible(): void
    {
        $browser = static::createClient();

        $crawler = $browser->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $this->assertPageTitleSame('Accueil');

        $title = $crawler->filter('h1');
        $this->assertEquals("Page d'accueil", $title->text());
    }

    public function testRoutingWorks(): void
    {
        $browser = static::createClient();

        $homepageCrawler = $browser->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $link = $homepageCrawler->filter('[data-testid="link"]')->link();
        $aboutCrawler = $browser->click($link);
        $this->assertResponseIsSuccessful();

        $this->assertPageTitleSame('A propos');
        $this->assertEquals('Page à propos', $aboutCrawler->filter('h1')->text());
    }

    public function testRedirectionWorks(): void
    {
        $browser = static::createClient();

        $crawler = $browser->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $link = $crawler->filter('[data-testid="redir"]')->link();
        $browser->click($link);

        $this->assertResponseRedirects('/about');
        $browser->followRedirect();

        $this->assertPageTitleSame('A propos');
    }

    public function testFormWorks(): void
    {
        $browser = static::createClient();

        $crawler = $browser->request('GET', '/form');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Envoyer')->form();
        $browser->submit($form, [
            'default[firstname]' => 'salut',
            'default[lastname]' => 'salut',
        ]);

        $this->assertResponseRedirects('/');
        $browser->followRedirect();

        $this->assertPageTitleSame('Accueil');
    }

    public function testAuthWorks(): void
    {
        $browser = static::createClient();
        $this->login($browser, 'email+0@email.fr');

        $crawler = $browser->request('GET', '/authenticated');
        $this->assertResponseIsSuccessful();

        $this->assertEquals('Page protégée', $crawler->filter('h1')->text());
        $this->assertEquals('email+0@email.fr', $crawler->filter('p')->text());
    }
}
