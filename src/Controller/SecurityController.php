<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/login', name: 'login_')]
class SecurityController extends AbstractController
{
    #[Route('/', name: 'login', methods: 'POST')]
    public function login(Request $request): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if($form->isSubmitted() && $form->isValid()) {
            dd($user);
        }
        return $this->json($form->getErrors());
    }
}
