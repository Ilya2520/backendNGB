<?php

namespace App\Users\Admin\Controller;


use App\Entity\BankAccount;
use App\Entity\Transaction;
use App\Entity\User1;
use App\Repository\BankAccountRepository;
use App\Repository\TalkRepository;
use App\Repository\TransactionRepository;
use App\Users\Enum\BankAccountStatusEnum;
use App\Users\Enum\TalkEnumStatuses;
use App\Users\Enum\TransactionStatusEnum;
use App\Users\Factory\BankAccountTypeFactory;
use App\Users\Factory\TransactionTypeFactory;
use App\Users\Repository\UserRepository;
use App\Users\Serializer\TalkSerializer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BankAccountRepository $bankAccountRepository,
        private readonly TransactionRepository $transactionRepository,
        private readonly TalkRepository $talkRepository,
        private readonly UserRepository $userRepository,
        private readonly TalkSerializer $talkSerializer
    ) {
    }
    
    #[Route('/admin/transactions', name: 'transactions')]
    #[IsGranted("ROLE_ADMIN")]
    public function getAllTransactions(): JsonResponse
    {
        $transactions = $this->transactionRepository->findAll();
        
        return $this->json($transactions, 200);
    }
    
    #[Route('/admin/transactions/moderate', name: 'moderate_transactions')]
    #[IsGranted("ROLE_ADMIN")]
    public function getAllTransactionsOnModerate(): JsonResponse
    {
        $transactions = $this->transactionRepository->findBy(
            [
                'Status' => TransactionStatusEnum::MODERATE,
            ]
        );
        
        return $this->json($transactions);
    }
    
    #[Route('/admin/transactions/change', name: 'moderate_transaction', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function changeTransactionStatus(
        Request $request
    ): JsonResponse {
        $decoded = json_decode($request->getContent());
        $transactionId = $decoded->id ?? null;
        $status =  $decoded->status ?? null;
        
        return $this->changeStatus($transactionId, $status, TransactionStatusEnum::STATUSES, $this->transactionRepository);
    }
    
    #[Route('/admin/bank_accounts', name: 'bank_accounts')]
    #[IsGranted("ROLE_ADMIN")]
    public function getAllBankAccounts(): JsonResponse
    {
        $bankAccounts = $this->bankAccountRepository->findAll();
        
        return $this->json($bankAccounts);
    }
    
    #[Route('/admin/bank_accounts/moderate', name: 'moderate_bank_accounts')]
    #[IsGranted("ROLE_ADMIN")]
    public function getAllBankAccountsOnModerate(): JsonResponse
    {
        $bankAccounts = $this->bankAccountRepository->findBy([
            "Status" => BankAccountStatusEnum::MODERATE
        ]);
        
        return $this->json($bankAccounts);
    }
    
    #[Route('/admin/bank_accounts/change', name: 'moderate_bank_account', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function changeBankAccountStatus(
        Request $request
    ): JsonResponse {
        $decoded = json_decode($request->getContent());
        $bankAccountId = $decoded->id ?? null;
        $status =  $decoded->status ?? null;
        
        return $this->changeStatus($bankAccountId, $status, BankAccountStatusEnum::STATUSES, $this->bankAccountRepository);
    }
    
    #[Route('/admin/users', name: 'users')]
    #[IsGranted("ROLE_ADMIN")]
    public function getAllUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        
        return $this->json($users);
    }
    
    #[Route('/admin/users/no_active', name: 'moderate_users')]
    #[IsGranted("ROLE_ADMIN")]
    public function getAllUsersAccountsOnModerate(): JsonResponse
    {
        $users = $this->userRepository->findBy([
            "isActive" => false
        ]);
        
        return $this->json($users);
    }
    
    #[Route('/admin/users/change', name: 'moderate_user', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function changeUser(
        Request $request
    ): JsonResponse {
        $decoded = json_decode($request->getContent());
        $userId = $decoded->id ?? null;
        $active =  $decoded->active ?? null;
        
        return $this->changeStatus($userId, $active, [true, false], $this->userRepository);
    }
    
    #[Route('/admin/talks', name: 'talks')]
    #[IsGranted("ROLE_ADMIN")]
    public function getAllTalks(): JsonResponse
    {
        $talks = $this->talkRepository->findAll();
        
        return $this->json($this->talkSerializer->fromArray($talks));
    }
    
    #[Route('/admin/talks/by_status', name: 'talks_by_status')]
    #[IsGranted("ROLE_ADMIN")]
    public function getAllTalksByStatus(Request $request): JsonResponse
    {
        $decoded = json_decode($request->getContent());
        $status =  $decoded->status ?? null;
        
        if (in_array($status, TalkEnumStatuses::STATUSES, true) === false){
            return $this->json(['message' => 'uncorrected status'],400);
        }
        
        $talks = $this->talkRepository->findBy([
            "Status" => $status
        ]);
        
        return $this->json($this->talkSerializer->fromArray($talks));
    }
    
    #[Route('/admin/talks/change', name: 'moderate_talk', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function changeTalk(
        Request $request
    ): JsonResponse {
        $decoded = json_decode($request->getContent());
        $talkId = $decoded->id ?? null;
        $status =  $decoded->status ?? null;
        
        return $this->changeStatus($talkId,  $status, TalkEnumStatuses::STATUSES, $this->talkRepository);
    }
    
    #[Route('/admin/create/bank_account_type', name: 'bank_account_type')]
    #[IsGranted("ROLE_ADMIN")]
    public function createBankAccountType(Request $request): JsonResponse
    {
        $decoded = json_decode($request->getContent());
        $type = $decoded->type ?? null;
        $conditions =  $decoded->conditions ?? null;
        
        $bankAccountTypeFactory = new BankAccountTypeFactory();
        $bankAccountType = $bankAccountTypeFactory->create($type, $conditions);
        $this->entityManager->persist($bankAccountType);
        
        return $this->json($bankAccountType, 200);
    }
    
    #[Route('/admin/create/transaction_type', name: 'transaction_type')]
    #[IsGranted("ROLE_ADMIN")]
    public function createTransactionType(Request $request): JsonResponse
    {
        $decoded = json_decode($request->getContent());
        $name = $decoded->name ?? null;
        $info = $decoded->info ?? null;
        $imageLink = $decoded->image ?? null;
        
        $transactionTypeFactory = new TransactionTypeFactory();
        $transactionType = $transactionTypeFactory->create($name, $info, $imageLink);
        
        $this->entityManager->persist($transactionType);
        
        return $this->json($transactionType, 200);
    }
    
    public function changeStatus(
        ?int $entityId,
        string|bool|null $newStatus,
        array $statuses,
        ServiceEntityRepository $repository
    ): JsonResponse {
        
        if ($entityId === null){
            return new JsonResponse(['message' => 'id is empty'], 400);
        }
        
        if ($newStatus === null or in_array($newStatus, $statuses, true) === false){
            return new JsonResponse(['message' => 'status is uncorrected'], 400);
        }
        
        /** @var User1|BankAccount|Transaction $entity */
        $entity = $repository->find($entityId);
        
        if ($entity === null) {
            return new JsonResponse(['error' => 'entity not found'], 404);
        }
        
        if (is_bool($newStatus)) {
            if ($entity->isActive() === $newStatus){
                return  new JsonResponse(['error' => 'entity already has this status'], 404);
            }
            $entity->setActive($newStatus);
        }
        else {
            if ($entity->getStatus() === $newStatus){
                return  new JsonResponse(['error' => 'entity already has this status'], 404);
            }
            $entity->setStatus($newStatus);
        }
        
        $this->entityManager->flush();
        
        return new JsonResponse(['message' => 'success'], 200);
    }
}
