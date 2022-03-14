<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\Residence;
use App\Entity\User;
use App\Form\EditResidenceType;
use App\Form\EditTenantFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function editresidence(int $id, Request $request): Response
    {
        $residence = $this->getDoctrine()->getRepository(Residence::class)->find($id);
        $form = $this->createForm(EditResidenceType::class, $residence);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($residence);
            $entityManager->flush();
        }
        return $this->renderForm('residence/editresidence.html.twig', [
            'form' => $form,
            'residence'=>$residence,
        ]);
    }
}
