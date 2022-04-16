<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/login', name: 'login_')]
class SecurityController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository,
                                UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/', name: 'login', methods: 'POST')]
    public function login(Request $request): JsonResponse
    {
        if($request->request->get('email') && $request->request->get('password')) {
            $user = $this->userRepository->findOneBy(['email' => $request->request->get('email')]);
            if($user) {
                if($this->userPasswordHasher->isPasswordValid($user, $request->request->get('password'))) {
                    $user->setApiToken($this->randomString(100));
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                    return $this->json($user, 201);
                } else {
                    return $this->json('Email or password not found', 404);
                }
            } else {
                return $this->json('Email or password not found', 404);
            }
        } else {
            return $this->json('Error not data', 400);
        }
    }

    private function randomString($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces [] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
