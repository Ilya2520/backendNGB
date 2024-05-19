<?php

namespace App\Users\Controller;

use App\Repository\BankAccountRepository;
use App\Repository\TalkRepository;
use App\Repository\TransactionRepository;
use App\Users\Factory\TalkFactory;
use App\Users\Interface\UserFetcherInterface;
use App\Users\Serializer\BankAccountSerializer;
use App\Users\Serializer\TalkSerializer;
use App\Users\Serializer\TransactionSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api', name: 'api_')]
class UserEntitiesController extends AbstractController
{
    public function __construct(
        private readonly TalkRepository $talkRepository,
        private readonly BankAccountRepository $bankAccountRepository,
        private readonly TransactionRepository $transactionRepository,
        private readonly UserFetcherInterface $fetcher,
        private readonly BankAccountSerializer $bankAccountSerializer,
        private readonly TalkSerializer $talkSerializer,
        private readonly TransactionSerializer $transactionSerializer
    ) {
    }

    #[Route('/user/talks', methods: ['GET'])]
    public function talks(): JsonResponse
    {
        $user = $this->fetcher->getAuthUser();
        $talks = $this->talkRepository->findBy(['User' => $user->getId()]);

        return $this->json($this->talkSerializer->fromArray($talks), 200);
    }
    
    #[Route('/user/talks/{id}', methods: ['GET'])]
    public function talk(int $id): JsonResponse
    {
        $user = $this->fetcher->getAuthUser();
        $talk = $this->talkRepository->find($id);
        
        if ($talk->getUser() !== $user){
            return $this->json(['error' => 'Access denied'], 403);
        }
        
        return $this->json($this->talkSerializer->forOutput($talk), 200);
    }

    #[Route('/user/bank_accs', methods: ['GET'])]
    public function bank_accs(): JsonResponse
    {
        $user = $this->fetcher->getAuthUser();
        $bankAccounts = $this->bankAccountRepository->findBy(["user" => $user->getUserId()]);

        return $this->json($this->bankAccountSerializer->fromArray($bankAccounts), 200);
    }
    
    #[Route('/user/bank_accs/{id}', methods: ['GET'])]
    public function bank_acc(int $id): JsonResponse
    {
        $user = $this->fetcher->getAuthUser();
        $bankAccount = $this->bankAccountRepository->find($id);
        
        if ($bankAccount->getUser() !== $user){
            return $this->json(['error' => 'Access denied'], 403);
        }
        
        return $this->json($this->bankAccountSerializer->forOutput($bankAccount), 200);
    }
    #[Route('/user/transactions', methods: ['GET'])]
    public function transactions(): JsonResponse
    {
        $user = $this->fetcher->getAuthUser();
        $result = [];
        $bankAccounts = $this->bankAccountRepository->findBy(["user" => $user->getUserId()]);
        foreach ($bankAccounts as $bankAccount){
            $result = array_merge($result, $this->transactionRepository->findBy(['fromBankAccount' => $bankAccount->getId()]));
        }

        return $this->json($this->transactionSerializer->fromArray($result), 200);
    }
    
    #[Route('/user/transactions/{id}', methods: ['GET'])]
    public function getTransaction(int $id): JsonResponse
    {
        $transaction = $this->transactionRepository->find($id);
        
        $user = $this->fetcher->getAuthUser();
        
        // Проверяем, имеет ли пользователь доступ к данной транзакции
        // Например, проверяем, что пользователь является владельцем банковского счета, связанного с этой транзакцией
        $fromBankAccount = $transaction->getFromBankAccount();
        if (!$fromBankAccount || $fromBankAccount->getUser() !== $user) {
            // Если у пользователя нет доступа к транзакции, возвращаем сообщение об ошибке
            return $this->json(['error' => 'Access denied'], 403);
        }
        
        // Возвращаем JSON-ответ с сериализованной транзакцией
        return $this->json($this->transactionSerializer->forOutput($transaction), 200);
    }
}