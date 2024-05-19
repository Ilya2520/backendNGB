<?php

namespace App\Users\Controller;

use App\Entity\BankAccount;
use App\Repository\BankAccountRepository;
use App\Users\Enum\BankAccountStatusEnum;
use App\Users\Factory\BankAccountFactory;
use App\Users\Factory\TransactionFactory;
use App\Users\Interface\UserFetcherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api', name: 'api_')]
class TransactionController extends AbstractController
{
    public function __construct(
        private readonly TransactionFactory $transactionFactory,
        private readonly UserFetcherInterface $fetcher,
        private readonly BankAccountRepository $bankAccountRepository
    ) {
    }
    #[Route('/create/transaction', methods: 'post')]
    public function index(
        ManagerRegistry $doctrine,
        Request $request,
    ): JsonResponse {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $user = $this->fetcher->getAuthUser();
        $from = $decoded->from;
        $to = $decoded->to;
        $amount = $decoded->amount;
        $text = $decoded->text ?? "";
        [$bankAccountFrom, $bankAccountTo] = $this->verifyBankAccounts($from, $to, $user->getId());
        
        if ($bankAccountFrom === null || $bankAccountTo === null) {
            // Возвращаем ошибку или сообщение об ошибке
            return $this->json(['error' => 'Invalid bank account data'], 400);
        }
        
        if ($this->verifyAmount($bankAccountFrom, $amount) === false){
            return $this->json(['error' => 'Invalid amount data'], 400);
        }
        
        if ($this->verifyBankAccountStatus($bankAccountFrom) === false){
            return $this->json(['error' => 'Your bank account status is not accept'], 400);
        }
        
        if ($this->verifyBankAccountStatus($bankAccountTo) === false){
            return $this->json(['error' => 'Forward bank account status is not accept'], 400);
        }
        
        $transaction = $this->transactionFactory->create($bankAccountFrom, $bankAccountTo, $amount, $text);
        
        $currentAmountFrom = floatval($bankAccountFrom->getAmount());
        $currentAmountTom = floatval($bankAccountTo->getAmount());
        
        $newAmountFrom = $currentAmountFrom - $amount;
        $newAmountTo = $currentAmountTom + $amount;

        $bankAccountFrom->setAmount((string)$newAmountFrom);
        $bankAccountTo->setAmount((string)$newAmountTo);
        
        $em->persist($bankAccountFrom);
        $em->persist($bankAccountTo);
        $em->persist($transaction);
        $em->flush();
        
        return $this->json(['message' => 'Transaction created Successfully']);
    }
    
    public function verifyBankAccounts(int $from, int $to, int $userId): ?array
    {
        if ($from === $to){
            return null;
        }
        
        $bankAccountFrom = $this->bankAccountRepository->find($from);
        $bankAccountTo = $this->bankAccountRepository->find($to);
        if($bankAccountFrom->getUser()->getId() === $userId && !is_null($bankAccountTo)){
            return [$bankAccountFrom, $bankAccountTo];
        }
        
        return null;
    }
    
    public function verifyBankAccountStatus(BankAccount $bankAccount): bool
    {
        if ($bankAccount->getStatus() === BankAccountStatusEnum::ACCEPT){
            return true;
        }
        
        return false;
    }
    public function verifyAmount(BankAccount $bankAccount, float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }
        
        if ($bankAccount->getAmount() > $amount){
            return true;
        }
        
        return false;
    }
}