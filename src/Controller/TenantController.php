<?php

namespace App\Controller;

use App\Entity\Residence;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TenantController extends AbstractController
{
    #[Route('/tenant-list', name: 'tenant-list')]
    public function index(): Response
    {
        if($this->getUser()){
            $users = $this->getDoctrine()->getRepository(User::class)->findUserByRole('["tenant"]');
            return $this->render('tenant/index.html.twig',[
                'users'=>$users
            ]);
        }
        return $this->render('login/index.html.twig');
    }
}