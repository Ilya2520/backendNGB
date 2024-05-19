<?php

namespace App\Users\Controller;

use App\Repository\TalkRepository;
use App\Users\Factory\MessageFactory;
use App\Users\Factory\TalkFactory;
use App\Users\Interface\UserFetcherInterface;
use App\Users\Serializer\MessageSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use function Symfony\Component\Clock\now;


#[Route('/api', name: 'api_')]
class MessageController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserFetcherInterface $fetcher,
        private readonly MessageFactory $messageFactory,
        private readonly TalkRepository $talkRepository
    ) {
    }
    #[Route('/create/talk/{id}/message', methods: 'post')]
    public function index(
        Request $request,
        int $id
    ): JsonResponse {
        $decoded = json_decode($request->getContent());
        $text = $decoded->text;
        $user = $this->fetcher->getAuthUser();
        
        $talk = $this->talkRepository->find($id);
        
        if ($talk->getUser() !== $user){
            return $this->json(['error' => 'Access denied'], 403);
        }
        
        $message = $this->messageFactory->create($talk, $user, $text);

        $this->entityManager->persist($message);
        $this->entityManager->flush();
        
        return $this->json(['message' => 'Bank Account created Successfully']);
    }
    
  
}