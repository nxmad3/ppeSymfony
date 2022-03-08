<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddTenantFormType;
use App\Form\EditOwnerForm;
use App\Form\EditTenantFormType;
use Symfony\Component\Form\FormTypeInterface;
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
    #[Route('/addtenant', name: 'addtenant')]

    public function addtenant(Request $request,): Response
    {
        $user = new User();
        $form = $this->createForm(AddTenantFormType::class, $user);
        $form->handleRequest($request);
        $user->setRoles(array("tenant"));
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($user);
            $entityManager->flush();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Le locataire a bien ete ajouter');
        }
        return $this->render('tenant/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}