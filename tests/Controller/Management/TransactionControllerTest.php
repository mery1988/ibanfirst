<?php


namespace App\Tests\Controller\Management;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TransactionControllerTest extends WebTestCase
{

    public function testHomePage()
    {
        $client = static::createClient();
        $client->request('GET', "/");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('p.h4.font-weight-normal', 'Bienvenue sur notre plateforme');
    }

    public function testListWallets()
    {
        $client = static::createClient();
        $client->request('GET', "/wallets");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h3', 'Liste des portefeuilles');
    }

    public function testDetailExistWallet()
    {
        $client = static::createClient();
        $client->request('GET', "/wallets/NDgyODY");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h3', 'Détails du portefeuille : NDgyODY');
    }

    public function testDetailNotFoundWallet()
    {
        $client = static::createClient();
        $client->request('GET', "/wallets/NOTFOUND");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('div.alert-warning', 'Portefeuille innexistant');
    }

    public function testListFianancialMvtsByExistWalletId()
    {
        $client = static::createClient();
        $client->request('GET', "/financial_movements/wallet/NDgyODY");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h3', 'Liste des mouvements financiers du portefeuille : NDgyODY');
    }


    public function testListFianancialMvtsByNotFoundWalletId()
    {
        $client = static::createClient();
        $client->request('GET', "/financial_movements/wallet/NOTFOUNDWALLET");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('div.alert-warning', 'Pas de mouvements financiers pour ce portefeuille');
    }

    public function testDetailExistFianancialMvtId()
    {
        $client = static::createClient();
        $client->request('GET', "/financial_movements/MTU1MDc4MA");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h3', 'Détails du Mouvement financier : MTU1MDc4MA');
    }


    public function testDetailNotFoundFianancialMvtId()
    {
        $client = static::createClient();
        $client->request('GET', "/financial_movements/NOTFOUNDFIANACIALMVT");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('div.alert-warning', 'Mouvement financier innexistant');
    }


}
