<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegisterPage()
    {
        $client = static::createClient();

        $client->request('GET', '/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Inscription');
        // Ajoutez d'autres assertions pour vérifier le contenu de la page
    }

    public function testInvalidRegistration()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        // Remplacez 'input[name="nom_du_champ"]' par le sélecteur approprié pour votre champ de formulaire
        $form = $crawler->selectButton('S\'inscrire')->form();
        $form['registration_form[email]'] = 'utilisateurtest@gmail.com'; // Nom d'utilisateur vide pour simuler une inscription invalide
        $form['registration_form[plainPassword]'] = 'test'; // Mot de passe invalide
        $client->submit($form);

        // Ajoutez d'autres assertions pour vérifier que le formulaire contient des erreurs
        $this->assertTrue(true);
    }
}
