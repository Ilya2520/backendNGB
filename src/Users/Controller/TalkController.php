<?php

namespace App\Users\Controller;

use App\Repository\TalkRepository;
use App\Users\Factory\TalkFactory;
use App\Users\Interface\UserFetcherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class TalkController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TalkFactory $talkFactory,
        private readonly TalkRepository $talkRepository,
        private readonly UserFetcherInterface $fetcher
    ) {
    }
    #[Route('/create/talk', methods: 'post')]
    public function index(
        Request $request,
    ): JsonResponse {
        $decoded = json_decode($request->getContent());
        $user = $this->fetcher->getAuthUser();
        
        $talk = $this->talkFactory->create($user);

        $this->entityManager->persist($talk);
        $this->entityManager->flush();
        
        return $this->json(['message' => 'Talk created Successfully']);
    }
}