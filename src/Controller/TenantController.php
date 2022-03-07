<?php

namespace App\Controller;

use App\Entity\Residence;
use App\Entity\User;
use App\Form\EditOwnerForm;
use App\Form\EditRepresentativeFormType;
use App\Form\EditTenantFormType;
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
            $users = $this->getDoctrine()->getRepository(User::class)->findUserByRole(User::TENANT);
            return $this->render('tenant/index.html.twig',[
                'users'=>$users
            ]);
        }
        return $this->render('login/index.html.twig');
    }

    #[Route('/edittenant/{id}', name: 'edittenant')]
    public function edittenant(int $id, Request $request): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(EditTenantFormType::class, $user);
        $form->handleRequest($request);
        return $this->renderForm('tenant/edit.html.twig', [
            'form' => $form,
        ]);
    }
}