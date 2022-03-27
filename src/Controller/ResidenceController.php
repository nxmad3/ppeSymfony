<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\Residence;
use App\Entity\User;
use App\Form\AddRepresentativeForm;
use App\Form\AddResidenceType;
use App\Form\EditRepresentativeFormType;
use App\Form\EditResidenceType;
use App\Form\EditTenantFormType;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\FileUploader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class ResidenceController extends AbstractController
{
    #[Route('/residence', name: 'residence')]
    public function index(): Response
    {
        if($this->getUser()){
            $idResidences = $this->getDoctrine()->getRepository(Rent::class)->getIdResidences();
            $residences = $this->getDoctrine()->getRepository(Rent::class)->GetRent($idResidences);
            $nb = $this->getDoctrine()->getRepository(Rent::class)->GetTotalResidence(date('Y-m-d'));
            return $this->render('residence/index.html.twig',[
                'residences'=>$residences,
                'nb' => $nb,
            ]);
        }
        return $this->render('login/index.html.twig');
    }
    #[Route('/editresidence/{id}', name: 'editresidence')]
    public function editresidence(int $id, Request $request,SluggerInterface $slugger, KernelInterface $appKernel,EntityManagerInterface $entityManager): Response
    {
        $residence = $this->getDoctrine()->getRepository(Residence::class)->find($id);
        $form = $this->createForm(EditResidenceType::class, $residence);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();


        if ($form->isSubmitted() && $form->isValid()) {
            $file=$request->files->get("edit_residence");
            $inventory_file = $file["inventory_file"];
            $photo_file = $file["file"];
            $targetDir = $appKernel->getProjectDir() . "/public/uploads";
            $fileUploader = new FileUploader($targetDir, $slugger);
            $residence
                ->setInventoryFile($fileUploader->upload($inventory_file))
                ->setFile($fileUploader->upload($photo_file));
            $entityManager->persist($residence);
            $entityManager->flush();
        }
        return $this->renderForm('residence/editresidence.html.twig', [
            'form' => $form,
            'residence'=>$residence,
        ]);
    }
    #[Route('/addResidence', name: 'addResidence')]
    public function addResidence(Request $request, SluggerInterface $slugger, KernelInterface $appKernel,EntityManagerInterface $entityManager)
    {
        $residence = new Residence();
        $form = $this->createForm(AddResidenceType::class, $residence);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file=$request->files->get("add_residence");
            $inventory_file = $file["inventory_file"];
            $photo_file = $file["file"];
            $targetDir = $appKernel->getProjectDir() . "/public/uploads";
            $fileUploader = new FileUploader($targetDir, $slugger);
            $residence
                ->setInventoryFile($fileUploader->upload($inventory_file))
                ->setFile($fileUploader->upload($photo_file));
            $entityManager->persist($residence);
            $entityManager->flush();
        }

        return $this->render('residence/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
