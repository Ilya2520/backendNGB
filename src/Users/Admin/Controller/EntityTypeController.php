<?php

namespace App\Users\Admin\Controller;

use App\Entity\BankAccountType;
use App\Entity\TransactionType;
use App\Repository\BankAccountTypeRepository;
use App\Repository\TransactionTypeRepository;
use App\Users\Factory\BankAccountTypeFactory;
use App\Users\Factory\TransactionTypeFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
class EntityTypeController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BankAccountTypeRepository $bankAccountTypeRepository,
        private readonly BankAccountTypeFactory $accountTypeFactory,
        private readonly TransactionTypeFactory $transactionTypeFactory,
        private readonly TransactionTypeRepository $transactionTypeRepository
    ) {
    }
    
    #[Route('/bank-account-types', methods: ['GET'])]
    public function getBankAccountTypes(): JsonResponse
    {
        $bankAccountTypes = $this->bankAccountTypeRepository->findAll();
        return $this->json($bankAccountTypes, Response::HTTP_OK);
    }
    
    #[Route('/transaction-types', methods: ['GET'])]
    public function getTransactionTypes(): JsonResponse
    {
        $transactionTypes = $this->transactionTypeRepository->findAll();
        return $this->json($transactionTypes, Response::HTTP_OK);
    }
    
    #[Route('/bank-account-types/{id}', methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN")]
    public function updateBankAccountType(Request $request, BankAccountType $bankAccountType): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        // Проверяем наличие ключей 'type' и 'conditions' в массиве данных
        if (isset($data['type'])) {
            $bankAccountType->setType($data['type']);
        }
        if (isset($data['conditions'])) {
            $bankAccountType->setConditions($data['conditions']);
        }
        
        $this->entityManager->flush();
        
        return $this->json($bankAccountType, Response::HTTP_OK);
    }
    
    #[Route('/bank-account-types/{id}', methods: ['DELETE'])]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteBankAccountType(BankAccountType $bankAccountType): JsonResponse
    {
        $this->entityManager->remove($bankAccountType);
        $this->entityManager->flush();
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
    
    #[Route('/transaction-types/{id}', methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN")]
    public function updateTransactionType(Request $request, TransactionType $transactionType): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        // Проверяем наличие ключей 'name', 'info' и 'imageLink' в массиве данных
        if (isset($data['name'])) {
            $transactionType->setName($data['name']);
        }
        if (isset($data['info'])) {
            $transactionType->setInfo($data['info']);
        }
        if (isset($data['imageLink'])) {
            $transactionType->setImageLink($data['imageLink']);
        }
        
        $this->entityManager->flush();
        
        return $this->json($transactionType, Response::HTTP_OK);
    }
    
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/transaction-types/{id}', methods: ['DELETE'])]
    public function deleteTransactionType(TransactionType $transactionType): JsonResponse
    {
        $this->entityManager->remove($transactionType);
        $this->entityManager->flush();
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
