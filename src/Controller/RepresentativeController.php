<?php

namespace App\Controller;

use App\Entity\Residence;
use App\Entity\User;
use App\Form\EditOwnerFormType;
use App\Form\EditRepresentativeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RepresentativeController extends AbstractController
{
    #[Route('/representative', name: 'representative')]
    public function index(): Response
    {
        if($this->getUser()){
            $users = $this->getDoctrine()->getRepository(User::class)->findUserByRoleAndGetId(User::REPRESENTATIVE);
            $count = $this->getDoctrine()->getRepository(Residence::class)->countNbResidence($users);

            return $this->render('representative/index.html.twig',[
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
    #[Route('/editrepresentative/{id}', name: 'editrepresentative')]
    public function editrepresentative(int $id, Request $request): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(EditRepresentativeFormType::class, $user);
        $form->handleRequest($request);
        return $this->renderForm('representative/edit.html.twig', [
            'form' => $form,
        ]);
    }

}
