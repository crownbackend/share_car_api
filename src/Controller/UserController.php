<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'profile_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'profile', methods: 'GET')]
    public function profile(): JsonResponse
    {
        return $this->json('test');
    }
}
