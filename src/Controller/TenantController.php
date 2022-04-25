<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\Residence;
use App\Entity\User;
use App\Form\AddLocationTenantFormType;
use App\Form\AddressFormType;
use App\Form\AddTenantFormType;
use App\Form\EditOwnerForm;
use App\Controller\Faker;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Faker\Factory;
use Symfony\Component\HttpFoundation\JsonResponse;
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

class  TenantController extends AbstractController
{
    #[Route('/tenant-list', name: 'tenant-list'), security("is_granted('ROLE_OWNER')")]
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

    #[Route('/tenant-rent', name: 'tenant-rent'), security("is_granted('ROLE_TENANT')")]
    public function listRent(): Response
    {
        $rents = $this->getDoctrine()->getRepository(Rent::class)->findLocationByUser($this->getUser()->getId());
        return $this->render('tenant/listRent.html.twig', [
            'rents' => $rents,
        ]);
    }

    #[Route('/edittenant/{id}', name: 'edittenant'), security(" is_granted('ROLE_OWNER') or is_granted('ROLE_TENANT')")]
    public function edittenant(int $id, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $locations = $this->getDoctrine()->getRepository(Rent::class)->findLocationByUser($id);
        $form = $this->createForm(EditTenantFormType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $password = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->flush();
        }

        return $this->renderForm('tenant/edit.html.twig', [
            'form' => $form,
            'locations' => $locations,
            'user' => $user,
        ]);
    }

    #[Route('/addtenant', name: 'addtenant'), security(" is_granted('ROLE_OWNER')")]
    public function addtenant(Request $request, MailerInterface $mailer, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(AddTenantFormType::class, $user);
        $form->handleRequest($request);
        $user->setRoles(array("ROLE_TENANT"));
        $faker = Factory::create('fr_FR');
        $user->setPassword($faker->password());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $email = (new TemplatedEmail())
                ->from('merciert60@gmail.com')
                ->to($user->getEmail())
                ->subject('identifiant et mot de passe de votre compte')
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
        if(InvalidArgumentException::class){
            $this->addFlash('error', 'Le locataire n\'a pas ete ajouter');
        }
        return $this->render('tenant/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/addTenantLocation/{id}', name: 'addTenantLocation'), security(" is_granted('ROLE_OWNER') or is_granted('ROLE_TENANT')")]
    public function addTenantLocation(int $id, Request $request): Response
    {
        $rent = new Rent();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(AddLocationTenantFormType::class, $rent);
        $form->handleRequest($request);
        $rent->setTenant($user);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rent);
            $entityManager->flush();
            $this->addFlash('success', 'Le locataire a bien ete ajouter');
        } else {
            $this->addFlash('error', 'Le locataire n\'a pas ete ajouter');
        }
        return $this->renderForm('tenant/addTenantLocation.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}