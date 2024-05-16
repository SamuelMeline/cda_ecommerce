<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProduitsControllerTest extends WebTestCase
{


    public function testLogin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $crawler = $client->getCrawler();
        $link = $crawler->selectLink('Se Connecter')->link();
        $client->click($link);

        // Assurez-vous que la page de connexion est atteinte en vérifiant par exemple le code de réponse HTTP ou le contenu de la page
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connectez-vous');
    }
}
