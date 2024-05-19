<?php

namespace App\Users\Controller;

use App\Users\Interface\UserFetcherInterface;
use App\Users\Repository\UserRepository;
use App\Users\Serializer\UserSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserFetcherInterface $fetcher,
        private readonly UserSerializer $userSerializer
    ) {
    }
    #[Route('/me', methods: 'get')]
    public function index(
    ): JsonResponse {
        $user = $this->fetcher->getAuthUser();
        
        return $this->json($this->userSerializer->forUser($user), 200);
    }
    
    #[Route('/update/user', methods: ['PUT'])]
    public function update(Request $request): JsonResponse
    {
        $decoded = json_decode($request->getContent(), true);
        $idFromResponse = $decoded['id'] ?? null;
        $user = $this->fetcher->getAuthUser();
        $userId = $user->getId();
        
        if ($idFromResponse === null) {
            return new JsonResponse(["message" => "ID is required"], 400);
        }
        
        if ($idFromResponse !== $userId and in_array("ROLE_ADMIN", $user->getRoles()) === false) {
            return new JsonResponse(["message" => "not allowed"], 400);
        }
        
        $userToUpdate = $this->userRepository->find($idFromResponse);
        
        if (!$userToUpdate) {
            return new JsonResponse(["message" => "User not found or access denied"], 404);
        }
        
        if (isset($decoded['email'])) {
            $userToUpdate->setEmail($decoded['email']);
        }
        if (isset($decoded['name'])) {
            $userToUpdate->setName($decoded['name']);
        }
        if (isset($decoded['surname'])) {
            $userToUpdate->setSurname($decoded['surname']);
        }
        if (isset($decoded['lastName'])) {
            $userToUpdate->setLastName($decoded['lastName']);
        }
        if (isset($decoded['phone'])) {
            $userToUpdate->setPhone($decoded['phone']);
        }
        if (isset($decoded['settings'])) {
            $userToUpdate->setSettings($decoded['settings']);
        }
        if (isset($decoded['username'])) {
            $userToUpdate->setUsername($decoded['username']);
        }
        
        $this->entityManager->persist($userToUpdate);
        $this->entityManager->flush();
        
        return $this->json(['message' => 'User updated successfully']);
    }
    
    #[Route('/delete/user', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {
        $decoded = json_decode($request->getContent(), true);
        $idFromResponse = $decoded['id'] ?? null;
        $user = $this->fetcher->getAuthUser();
        $userId = $user->getId();
        
        if ($idFromResponse === null) {
            return new JsonResponse(["message" => "ID is required"], 400);
        }
        
        if ($idFromResponse !== $userId and in_array("ROLE_ADMIN", $user->getRoles()) === false) {
            return new JsonResponse(["message" => "not allowed"], 400);
        }
        
        $userToDelete = $this->userRepository->find($idFromResponse);
        
        if (!$userToDelete) {
            return new JsonResponse(["message" => "User not found or access denied"], 404);
        }
        
        $this->entityManager->remove($userToDelete);
        $this->entityManager->flush();
        
        return $this->json(['message' => 'User deleted successfully']);
    }
}