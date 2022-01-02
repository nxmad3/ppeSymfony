<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{

    public function index(Request $request): Response
    {
        // Exercice : rediriger l'utilisateur sur la route "bonjour" en lui passant le paramètre "user"
        $user = $request->query->get('user', 'world');

        return $this->redirectToRoute('bonjour', [
            'prenom' => $user,
        ]);
    }

    public function bonjour(string $prenom = 'world'): Response
    {
        return $this->render('default/bonjour.html.twig', [
            'prenom' => $prenom,
        ]);
    }
}
