<?php


namespace App\Controller\Management;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Helper\TransactionHelper;


class TransactionController extends AbstractController
{
    const ERROR_CODE = 404;
    /**
     * @var TransactionHelper
     */
    protected $transactionHelper;

    public function __construct(TransactionHelper $transactionHelper)
    {
        $this->transactionHelper = $transactionHelper;
    }

    /**
     * Get List of all wallets
     * @Route("/", name="home_page")
     */
    public function index()
    {
        return $this->render('transaction/index.html.twig');
    }


    /**
     * Get List of all wallets
     * @Route("/wallets", name="list_wallets")
     */
    public function getListWallets()
    {
        $wallets = $this->transactionHelper->fetchWallets();
        if($wallets['code'] === self::ERROR_CODE && $wallets['data'] === null) $this->addFlash("warning", "Liste de portefeuilles vide");
        return $this->render('transaction/list_wallets.html.twig', ['wallets' => $wallets['data']['wallets']]);
    }

    /**
     * Get details wallet
     * @param string $id
     * @Route("/wallets/{id}", name="detail_wallet")
     */
    public function getDetailWallet(string $id)
    {
        $wallet = $this->transactionHelper->findWalletById($id);
        if($wallet['code'] === self::ERROR_CODE && $wallet['data'] === null) $this->addFlash("warning", "Portefeuille innexistant");
        return $this->render('transaction/detail_wallet.html.twig', ['detailWallet' => $wallet['data']['wallet']]);
    }

    /**
     * Get List financial Movements of Wallet
     * @param string $walletId
     * @Route("/financial_movements/wallet/{walletId}", name="list_financial_movements")
     */
    public function getListfinancialMovements(string $walletId)
    {
        $financialMovements = $this->transactionHelper->getfinancialMovementsByWalletId($walletId);
        if($financialMovements['code'] === self::ERROR_CODE && $financialMovements['data'] === null) $this->addFlash("warning", "Pas de mouvements financiers pour ce portefeuille");
        return $this->render('transaction/list_financial_movements.html.twig', ['financialMovements' => $financialMovements['data'] ? $financialMovements['data']['financialMovements'] : null, 'walletId' => $walletId]);

    }

    /**
     * Get details financial Movement
     * @param string $id
     * @Route("/financial_movements/{id}", name="detail_financial_movement")
     */
    public function getDetailfinancialMovement(string $id)
    {
        $financialMovement = $this->transactionHelper->findfinancialMovementsById($id);
        if($financialMovement['code'] === self::ERROR_CODE && $financialMovement['data'] === null) $this->addFlash("warning", "Mouvement financier innexistant");
        return $this->render('transaction/detail_financial_mouvement.html.twig', ['financialMovement' => $financialMovement['data']['financialMovement']]);

    }

    /**
     * Get list of all financial Movements
     * @param
     * @Route("/financial_movements", name="list_all_financial_movements")
     */
    public function fetchAllfinancialMovements()
    {
        $financialMovements = $this->transactionHelper->findfinancialMovements();
        if($financialMovements['code'] === self::ERROR_CODE && $financialMovements['data'] === null) $this->addFlash("warning", "Mouvement financier innexistant");
        return $this->render('transaction/list_financial_movements.html.twig', ['financialMovements' => $financialMovements['data']['financialMovements']]);

    }


}
