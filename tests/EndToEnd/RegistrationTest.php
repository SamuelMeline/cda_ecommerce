<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class RegistrationTest extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient(
            [
                'browser' => PantherTestCase::FIREFOX
            ]
        );

        $client->request('GET', 'http://localhost:8000/inscription');
        $this->assertSelectorTextContains('h1', 'Inscription');
    }
}
