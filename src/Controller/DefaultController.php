<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends AbstractController
{

    public function index(Request $request): Response
    {

        if($this->getUser()){
            $page = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
            return $this->render('default/index.html.twig',[
                'user'=>$page
            ]);
        }
        return $this->render('login/index.html.twig');
    }

}
