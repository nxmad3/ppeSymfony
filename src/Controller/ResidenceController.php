<?php

namespace App\Controller;

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
            $residence = $this->getDoctrine()->getRepository(Residence::class)->findAll();
            return $this->render('tenant/index.html.twig',[
                'residences'=>$residence
            ]);
        }
        return $this->render('login/index.html.twig');
    }
}
