<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testDashboardNotLogged(): void
    {
        $client = static::createClient();
        $client->request('GET', '/dashboard');

        $this->assertResponseRedirects();
    }

    public function testEditNotLogged(): void
    {
        $client = static::createClient();
        $client->request('GET', '/user');

        $this->assertResponseRedirects();
    }

    private function createLoggedUser(): KernelBrowser
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('jane@lingoda.com');

        $client->loginUser($testUser);

        return $client;
    }

    public function testDashboardLogged(): void
    {
        $client = $this->createLoggedUser();
        // test e.g. the profile page
        $client->request('GET', '/dashboard');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard page');
    }


    public function testEditLogged(): void
    {
        $client = $this->createLoggedUser();

        // test e.g. the profile page
        $client->request('GET', '/user');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Edit User');
    }

    public function testEditChangeData(): void
    {
        $client = $this->createLoggedUser();
        $crawler = $client->request('GET', '/user');
        $buttonCrawlerNode = $crawler->selectButton('Save');

        $form = $buttonCrawlerNode->form();
        $data = ['user_form[firstName]' => 'TestUser'];
        $client->submit($form, $data);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dashboard page');
        $this->assertSelectorTextContains('div', 'Your changes were saved!');
    }


}
