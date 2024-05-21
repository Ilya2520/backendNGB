<?php

namespace App\Users\Controller;

use App\Entity\User1;
use App\Users\Factory\UserFactory;
use App\Users\Interface\UserFetcherInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle;
use  Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserLogin extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserFactory $userFactory,
    ) {
    }
    
    #[Route('api/register', name: 'register', methods: 'post')]
    public function register(Request $request): JsonResponse
    {
        $decoded = json_decode($request->getContent());
        $email = $decoded->email;
        $password = $decoded->password;
        
        if ($email === null || $password === null){
            return $this->json(['message'=> 'empty fields'], 400);
        }
        
        if (empty($email) || empty($password)) {
            return $this->json(['message' => 'Empty email or password'], 400);
        }
        
        $validator = Validation::createValidator();
        $emailConstraint = new Assert\Email();
        $passwordConstraint = new Assert\Length([
            'min' => 8,
            'max' => 25,
            'minMessage' => 'Your password must be at least {{ limit }} characters long',
            'maxMessage' => 'Your password cannot be longer than {{ limit }} characters',
        ]);
        
        $emailErrors = $validator->validate(
            $email,
            $emailConstraint
        );
        
        $passwordErrors = $validator->validate($password, $passwordConstraint);
        
        if (count($emailErrors) > 0) {
            // Вывод ошибки в случае невалидного email
            return $this->json(['message' => 'Invalid email address'], 400);
        }
        
        if (count($passwordErrors) > 0) {
            // Вывод ошибки в случае невалидного пароля
            return $this->json(['message' => $passwordErrors[0]->getMessage()], 400);
        }
        
        try {

            $user = $this->userFactory->create($email, $password);
            
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            return $this->json(['message' => 'Email already exists'], 400);
        }
        // Возвращаем ответ клиенту
        return $this->json(['message'=> 'Successful registration']);
    }
}