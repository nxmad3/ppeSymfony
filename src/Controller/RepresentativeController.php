<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\Residence;
use App\Entity\User;
use App\Form\AddLocationTenantFormType;
use App\Form\AddRepresentativeForm;
use App\Form\AddTenantFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\EditOwnerFormType;
use App\Form\EditRepresentativeFormType;
use Faker\Factory;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Component\Pager\PaginatorInterface;

// Nous appelons le bundle KNP Paginator

class RepresentativeController extends AbstractController
{
    #[Route('/representative', name: 'representative'), security("is_granted('ROLE_OWNER')")]
    public function index(): Response
    {
        if ($this->getUser()) {
            $users = $this->getDoctrine()->getRepository(User::class)->findUserByRoleAndGetId(User::REPRESENTATIVE);
            $representative = $this->getDoctrine()->getRepository(Rent::class)->countNbResidence($users);

            return $this->render('representative/index.html.twig', [
                'users' => $representative,
            ]);
        }
        return $this->render('login/index.html.twig');
    }

    #[Route('/location-list/{slug}', name: 'location-list')]
    public function edit(int $slug): Response
    {
        if ($this->getUser()) {
            $residences = $this->getDoctrine()->getRepository(Residence::class)->GetResidence($slug);
            return $this->render('tenant/list-residence.html.twig', [
                'residences' => $residences
            ]);
        }
        return $this->render('login/index.html.twig');
    }

    #[Route('/editrepresentative/{id}', name: 'editrepresentative'), security("is_granted('ROLE_OWNER')")]
    public function editRepresentative(int $id, Request $request): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(EditRepresentativeFormType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->renderForm('representative/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/addRepresentative', name: 'addRepresentative'), security(" is_granted('ROLE_OWNER')")]
    public function addRepresentative(Request $request, MailerInterface $mailer, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(AddRepresentativeForm::class, $user);
        $form->handleRequest($request);
        $user->setRoles(array("ROLE_REPRESENTATIVE"));
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
            $this->addFlash('success', 'Le mandataire a bien ete ajouter');
        }
        return $this->render('representative/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
