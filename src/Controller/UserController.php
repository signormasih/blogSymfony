<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/{condition}', name: 'app_user')]
    public function index(string $condition): Response
    {
        if($condition == "register"){
            return $this->render('user/index.html.twig', [
                'loginOrRegister' => 'register',
            ]);
        }elseif($condition == "login"){
            return $this->render('user/index.html.twig', [
                'loginOrRegister' => 'login',
            ]);
        }
        
    }
}
