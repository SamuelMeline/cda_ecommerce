<?php 

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class RealWorldTest extends PantherTestCase
{
    public function testGetStarted()
    {
        // Create Firefox client
        $client = static::createPantherClient();

        // Request the URL
        $crawler = $client->request('GET', 'https://api-platform.com');

        // Ensure the "Get started" link is present before clicking
        $link = $crawler->selectLink('Getting started');
        $this->assertGreaterThan(0, $link->count(), 'Link "Getting started" not found');

        // Click the "Get started" link
        $crawler = $client->clickLink('Getting started');

        // Wait for the element with ID 'installing-the-framework' to be visible
        $crawler = $client->waitFor('#installing-the-framework', 2);
        $crawler = $client->waitForVisibility('#installing-the-framework', 2);

        // Ensure the element is present before interacting with it
        $this->assertGreaterThan(0, $crawler->filter('#installing-the-framework')->count(), 'Element "#installing-the-framework" not found');

        // Perform assertions
        $title = $crawler->filter('#installing-the-framework')->text();
        $this->assertEquals(strtolower('Installing the framework'), strtolower($title));
        $this->assertStringContainsString('framework', strtolower($title));
        $this->assertSelectorIsVisible('#installing-the-framework');

        // Take a screenshot
        $client->takeScreenshot('screen.png');
    }
}
