<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    #[Route('/user/{condition}', name: 'app_user')]
    public function index(string $condition, EntityManagerInterface $entityManager, Request $request,
    UserPasswordHasherInterface $passwordHasher): Response
    {
        $session = $request->getSession();
        $userisLogin = $session->get('userisLogin');

        if($condition == "logout"){
            $session->set('userisLogin', '');
            $session->set('username', '');
            $session->set('isadmin', '');
            
            return $this->redirect('/');
        }

        if($userisLogin == '1'){
            return $this->redirect('/');
        }
        if ($request->isMethod('post') && $condition == "register") {
            $username = $request->get('username');
            $password = $request->get('password');
            $confirm_password = $request->get('confirm_password');
            $email = $request->get('email');

            if($password !== $confirm_password){
                return $this->render('user/index.html.twig', [
                    'loginOrRegister' => 'register',
                    'err' => "پسورد با پسورد مجدد برابر نیست"
                ]);
            }

            $repo_username = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
            if($repo_username){
                return $this->render('user/index.html.twig', [
                    'loginOrRegister' => 'register',
                    'err' => "نام کاربری در سیستم وجود دارد"
                ]);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->render('user/index.html.twig', [
                    'loginOrRegister' => 'register',
                    'err' => "قالب ایمیل صحیح نمی‌باشد"
                ]);
            }

            $repo_email = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if($repo_email){
                return $this->render('user/index.html.twig', [
                    'loginOrRegister' => 'register',
                    'err' => "ایمیل در سیستم وجود دارد"
                ]);
            }

            $user = new User();
            $user->setEmail($email);
            $user->setRole('user');
            $hashedPassword = $password;
            $user->setPassword($hashedPassword);
            $user->setUsername($username);

            
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($user);
            // actually executes the queries (i.e. the INSERT query)
            
            $entityManager->flush();
            
            $session->set('userisLogin', '1');
            $session->set('username', $username);

            return $this->redirect('/');

        }elseif ($request->isMethod('post') && $condition == "login"){
            $username = $request->get('username');
            $password = $request->get('password');
            $repo_user = $entityManager->getRepository(User::class)->findOneBy(
                [
                    'username' => $username,
                    'password' => $password
                ]
            );
            if(!$repo_user){
                return $this->render('user/index.html.twig', [
                    'loginOrRegister' => 'login',
                    'err' => "نام‌کاربری یا رمز عبور اشتباه می‌باشد"
                ]);
            }
            $repo_user_isAdmin = $entityManager->getRepository(User::class)->findOneBy(
                [
                    'username' => $username,
                    'role' => 'admin'
                ]
            );
            if($repo_user_isAdmin){
                $session->set('isadmin', '1');
            }
            $session->set('userisLogin', '1');
            $session->set('username', $username);

            return $this->redirect('/');
        }
        if($condition == "register"){
            return $this->render('user/index.html.twig', [
                'loginOrRegister' => 'register',
                'err' => ""
            ]);
        }elseif($condition == "login"){
            return $this->render('user/index.html.twig', [
                'loginOrRegister' => 'login',
                'err' => ""
            ]);
        }
        
    }
}
