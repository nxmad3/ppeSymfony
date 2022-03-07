<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\Residence;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResidenceController extends AbstractController
{
    #[Route('/residence', name: 'residence')]
    public function index(): Response
    {
        if($this->getUser()){
            $residences = $this->getDoctrine()->getRepository(Rent::class)->GetRent();
            $nb = $this->getDoctrine()->getRepository(Rent::class)->GetTotalResidence();
            return $this->render('residence/index.html.twig',[
                'residences'=>$residences,
                'nb' => $nb
            ]);
        }
        return $this->render('login/index.html.twig');
    }
}
