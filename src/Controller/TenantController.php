<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddTenantFormType;
use App\Form\EditOwnerForm;
use App\Controller\Faker;
use Faker\Factory;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Form\EditTenantFormType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class TenantController extends AbstractController
{
    #[Route('/tenant-list', name: 'tenant-list')]
    public function index(): Response
    {
        if ($this->getUser()) {
            $users = $this->getDoctrine()->getRepository(User::class)->findUserByRole(User::TENANT);
            return $this->render('tenant/index.html.twig', [
                'users' => $users
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
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->renderForm('tenant/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/addtenant', name: 'addtenant')]
    public function addtenant(Request $request,MailerInterface $mailer,UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(AddTenantFormType::class, $user);
        $form->handleRequest($request);
        $user->setRoles(array("tenant"));
        $faker = Factory::create('fr_FR');
        $user->setPassword($faker->password());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $email = (new TemplatedEmail())
                ->from('merciert60@gmail.com')
                ->to($user->getEmail())
                ->subject('Confirmation dinscription')
                ->htmlTemplate('tenant/emailadd.html.twig')
                ->context([
                    'firstname' => $user->getname(),
                    'lastname' => $user->getLastname(),
                    'password' => $user->getPassword(),
                    'Adremail' => $user->getEmail(),
                ]);
            $mailer->send($email);
            $password = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
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