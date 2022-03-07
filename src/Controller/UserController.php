<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    #[Route('/user/{slug}', name: 'user-profil')]
    public function show(int $slug): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findByID($slug);
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/user/', name: 'profil')]
    public function edit(): Response
    {

        $user = $this->getDoctrine()->getRepository(User::class)->findByID($this->getUser()->getId());
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}
