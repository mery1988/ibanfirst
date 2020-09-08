<?php
namespace App\Tests\Helper;

use App\Helper\TransactionHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class TransactionHelperTest extends  WebTestCase
{


    private $transHelper;

    public function testTranslactionHelper()
    {
        self::bootKernel();
        $this->transHelper = static::$container->get(TransactionHelper::class);
        $headerRequest = $this->transHelper->getWsseHeader();
        $allWallets = $this->transHelper->fetchWallets();
        $walletExist = $this->transHelper->findWalletById("NDgyODY");
        $walletNotFound = $this->transHelper->findWalletById("NOTFOUND");
        $financialMVTWalletExist = $this->transHelper->getfinancialMovementsByWalletId("NDgyODY");
        $financialMVTWalletNotFound = $this->transHelper->getfinancialMovementsByWalletId("NOTFOUND");
        $financialMVTExist = $this->transHelper->findfinancialMovementsById('MTE5OTU2Nw');
        $financialMVTNotFound = $this->transHelper->findfinancialMovementsById("NOTFOUNDFINANCIALMVTS");
        $results = [$allWallets ,$walletExist,$walletNotFound , $financialMVTWalletExist,$financialMVTWalletNotFound,$financialMVTExist,$financialMVTNotFound];
        $res = [];

        foreach ($results as $result){
            if($result['code'] === 200 || $result['code'] === 201 ){
                $res[] = $result['data'];
            }else{
                $res[] = "error".$result['code'];
            }
        }
        $finalRes = ["headers" => $headerRequest , "results" => $res];
        $this->assertGreaterThan(0, count($finalRes));
        $this->assertStringContainsString('PasswordDigest', $headerRequest['X-WSSE']);

        return $finalRes;
    }
}
