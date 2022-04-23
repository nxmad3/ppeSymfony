<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\Residence;
use App\Form\CommentaireResidenceType;
use App\Repository\RentRepository;
use App\Entity\User;
use App\Form\AddLocationTenantFormType;
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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\FileUploader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\File;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ResidenceController extends AbstractController
{
    #[Route('/residence', name: 'residence') , security("is_granted('ROLE_OWNER')")]
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
    #[Route('/editresidence/{id}', name: 'editresidence') , security("is_granted('ROLE_REPRESENTATIVE') or is_granted('ROLE_OWNER')")]
    public function editresidence(int $id, Request $request,SluggerInterface $slugger, KernelInterface $appKernel,EntityManagerInterface $entityManager): Response
    {
        $residence = $this->getDoctrine()->getRepository(Residence::class)->find($id);
        $form = $this->createForm(EditResidenceType::class, $residence);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $inv = $form->get('invotory')->getData();

            if ($inv)
            {
                $originalFilename = pathinfo($inv->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$inv->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $inv->move(
                        'uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $residence->setInventoryFile($newFilename);
            }
            $file = $form->get('image')->getData();

            if ($file)
            {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        'uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $residence->setFile($newFilename);
            }
            $entityManager->persist($residence);
            $entityManager->flush();
        }
        return $this->renderForm('residence/editresidence.html.twig', [
            'form' => $form,
            'residence'=>$residence,
        ]);
    }
    #[Route('/addResidence', name: 'addResidence') , security("is_granted('ROLE_REPRESENTATIVE') or is_granted('ROLE_OWNER')")]
    public function addResidence(Request $request, SluggerInterface $slugger, KernelInterface $appKernel,EntityManagerInterface $entityManager)
    {
        $residence = new Residence();
        $form = $this->createForm(AddResidenceType::class, $residence);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            if ($file)
            {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        'uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $residence->setFile($newFilename);
            }
            $inv = $form->get('invotory')->getData();

            if ($inv)
            {
                $originalFilename = pathinfo($inv->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$inv->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $inv->move(
                        'uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $residence->setInventoryFile($newFilename);
            }
            $entityManager->persist($residence);
            $entityManager->flush();
        }

        return $this->render('residence/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/infoResidence/{id}', name: 'infoResidence') , security("is_granted('ROLE_REPRESENTATIVE') or is_granted('ROLE_OWNER') or is_granted('ROLE_TENANT')")]
    public function deleteResidence(int $id, Request $request,SluggerInterface $slugger, KernelInterface $appKernel,EntityManagerInterface $entityManager): Response
    {
        $residence = $this->getDoctrine()->getRepository(Rent::class)->findAllRentsById(2)  ;
        $form = $this->createForm(CommentaireResidenceType::class, $residence);
        $form->handleRequest($request);
        $entityManager->persist($residence);
        $entityManager->flush();
        return $this->renderForm('residence/info.html.twig', [
            'form' => $form,
            'residence' => $residence,
        ]);
    }
}
