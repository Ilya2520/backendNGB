<?php

namespace App\Users\Controller;

use App\Entity\BankAccount;
use App\Entity\User1;
use App\Repository\BankAccountRepository;
use App\Repository\BankAccountTypeRepository;
use App\Users\Enum\BankAccountStatusEnum;
use App\Users\Factory\BankAccountFactory;
use App\Users\Interface\UserFetcherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/api', name: 'api_')]
class BankAccountController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BankAccountFactory $bankAccountFactory,
        private readonly BankAccountRepository $bankAccountRepository,
        private readonly BankAccountTypeRepository $bankAccountTypeRepository,
        private readonly UserFetcherInterface $fetcher
    ) {
    }
    
    #[Route('/create/bank_account', methods: 'post')]
    public function index(
        Request $request,
    ): JsonResponse {
        $decoded = json_decode($request->getContent());
        $user = $this->fetcher->getAuthUser();
        $info = $decoded->info ?? [];
        $bankAccountTypeId = $decoded->id ?? [];
        
        $bankAccountType = $this->bankAccountTypeRepository->find($bankAccountTypeId);
        
        if ($bankAccountType === null){
            return $this->json(['message' => 'Bank Account type not found'], 404);
        }
        
        $bankAccount = $this->bankAccountFactory->create($user, $bankAccountType, array($info));

        $this->entityManager->persist($bankAccount);
        $this->entityManager->flush();
        
        return $this->json(['message' => 'Bank Account created Successfully']);
    }
    
    #[Route('/update/bank_account', methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN")]
    public function update(Request $request): JsonResponse
    {
        $decoded = json_decode($request->getContent(), true);
        $user = $this->fetcher->getAuthUser();
        $id = $decoded['id'] ?? null;
        $amount = $decoded['amount'] ?? null;
        $status = $decoded['status'] ?? null;
        $type = $decoded['type'] ?? null;
        
        if ($id === null) {
            return new JsonResponse(["message" => "ID is required"], 400);
        }
        
        $bankAccount = $this->bankAccountRepository->find($id);
        
        if (!$bankAccount || (!$this->isOwnerOrAdmin($bankAccount, $user))) {
            return new JsonResponse(["message" => "Bank account not found or access denied"], 404);
        }
        
        if ($amount !== null && !$this->verifyAmount($amount)) {
            return new JsonResponse(["message" => "Invalid amount"], 400);
        }
        
        if ($status !== null && !$this->verifyStatus($status)) {
            return new JsonResponse(["message" => "Invalid status"], 400);
        }
        
        if ($type !== null) {
            $bankAccountType = $this->bankAccountRepository->find($type);
            if (!$bankAccountType) {
                return new JsonResponse(["message" => "Invalid bank account type"], 400);
            }
            $bankAccount->setType($bankAccountType);
        }
        
        if ($amount !== null) {
            $bankAccount->setAmount($amount);
        }
        if ($status !== null) {
            $bankAccount->setStatus($status);
        }
        
        $this->entityManager->persist($bankAccount);
        $this->entityManager->flush();
        
        return $this->json(['message' => 'Bank Account updated successfully']);
    }
    #[Route('/delete/bank_account', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {
        $decoded = json_decode($request->getContent(), true);
        $user = $this->fetcher->getAuthUser();
        $id = $decoded['id'] ?? null;
        
        if ($id === null) {
            return new JsonResponse(["message" => "ID is required"], 400);
        }
        
        $bankAccount = $this->bankAccountRepository->find($id);
        
        if (!$bankAccount || (!$this->isOwnerOrAdmin($bankAccount, $user))) {
            return new JsonResponse(["message" => "Bank account not found or access denied"], 404);
        }
        
        $this->entityManager->remove($bankAccount);
        $this->entityManager->flush();
        
        
        return $this->json(['message' => 'Bank Account deleted successfully']);
    }
    
    private function verifyAmount($amount): bool
    {
        return is_numeric($amount) && $amount >= 0;
    }
    
    private function verifyStatus($status): bool
    {
        return in_array($status, BankAccountStatusEnum::STATUSES);
    }
    
    private function isOwnerOrAdmin(BankAccount $bankAccount, User1 $user): bool
    {
        return $bankAccount->getUser() === $user || in_array('ROLE_ADMIN', $user->getRoles());
    }
}