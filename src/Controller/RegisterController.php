<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'register_')]
class RegisterController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'register', methods: 'POST')]
    public function register(Request $request): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $passwordHash = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($passwordHash);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->json($user);
        }
        return $this->json($form->getErrors(), 400);
    }
}
