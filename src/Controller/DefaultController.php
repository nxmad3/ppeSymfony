<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function index(Request $request): Response
    {

        if ($this->getUser()) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
            return $this->render('default/index.html.twig', [
                'user' => $user
            ]);
        }
        return $this->render('login/index.html.twig');
    }

    #[Route('/deleteuser', name: 'deleteuser')]
    public function deleteUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($this->getUser());
        $em->flush();
        return $this->render('login/index.html.twig');
    }
}
