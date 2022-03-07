<?php

namespace App\Controller;

use App\Entity\Residence;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RepresntativeController extends AbstractController
{
    #[Route('/representative-list', name: 'representative-list')]
    public function index(): Response
    {
        if($this->getUser()){
            $users = $this->getDoctrine()->getRepository(User::class)->findUserByRoleAndGetId('["representative"]');
            $count = $this->getDoctrine()->getRepository(Residence::class)->countNbResidence($users);

            return $this->render('represntative/index.html.twig',[
                'users'=>$count
            ]);
        }
        return $this->render('login/index.html.twig');
    }
    #[Route('/location-list/{slug}', name: 'location-list')]
    public function edit(int $slug): Response
    {
        if($this->getUser()){
            $residences = $this->getDoctrine()->getRepository(Residence::class)->GetResidence($slug);
            return $this->render('tenant/list-residence.html.twig',[
                'residences'=>$residences
            ]);
        }
        return $this->render('login/index.html.twig');
    }
}
