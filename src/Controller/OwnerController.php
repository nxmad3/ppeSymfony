<?php

namespace App\Controller;

use App\Entity\Residence;
use App\Entity\User;
use App\Form\EditOwnerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;

class OwnerController extends AbstractController
{
    #[Route('/editowner/{id}', name: 'editowner')]
    public function edit(int $id, Request $request): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(EditOwnerFormType::class, $user);
        $form->handleRequest($request);
        return $this->renderForm('owner/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/owner/', name: 'owner')]
    public function index(Request $request): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findUserByRole(User::OWNER);
        dd($users);

        return $this->render('owner/index.html.twig', [
            'users' => $users,
        ]);
    }
}