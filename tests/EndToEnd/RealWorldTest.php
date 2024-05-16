<?php 

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class RealWorldTest extends PantherTestCase
{
    public function testGetStarted()
    {
        $clientChrome = Client::createChromeClient();
        $clientFirefox = Client::createFirefoxClient();

        $clientChrome->request('GET', 'https://api-platform.com');
        $clientFirefox->clickLink('Get started');

        $crawler = $clientFirefox->waitFor('#installing-the-framework', 2);
        $crawler = $clientFirefox->waitForVisibility('#installing-the-framework', 2);

        $title = $crawler->filter('#installing-the-framework')->text();
        $this->assertEquals('Installing the framework', $title);
        $this->assertSelectorTextContains('#installing-the-framework', 'framework');
        $this->assertSelectorIsVisible('#installing-the-framework');
        $clientFirefox->takeScreenshot('screen.png');
    }
}