<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\PostType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class manageAdmin extends AbstractController
{
    #[Route('/manageAdmin', name: 'manageAdmin')]
    public function manageAdmin(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $userisLogin = $session->get('userisLogin');
        $isadmin = $session->get('isadmin');

        if($userisLogin != '1' && $isadmin != '1'){
            return $this->redirect('/');
        }

        $repository_user = $entityManager->getRepository(User::class);
        $allUser = $repository_user->findAll();

        $repository_category = $entityManager->getRepository(Category::class);
        $allCatgory = $repository_category->findAll();

        $repository_PostType = $entityManager->getRepository(PostType::class);
        $allPostType = $repository_PostType->findAll();

        return $this->render('admin/manageAdmin.html.twig', 
            [
                'allUser' => $allUser,
                'allCatgory' => $allCatgory,
                'allPostType' => $allPostType
            ]
        );
    }
    #[Route('/manageAdmin/deleteUser/{id}', name: 'manageAdmin_deleteUser')]
    public function manageAdminDeleteUser(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $session = $request->getSession();
        $userisLogin = $session->get('userisLogin');
        $isadmin = $session->get('isadmin');
        if($userisLogin != '1' && $isadmin != '1'){
            return $this->json([]);
        }
        $userforDelete = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $entityManager->remove($userforDelete);
        $entityManager->flush();
        return $this->json(["status" => 1]);
    }
    #[Route('/manageAdmin/upgradeUser/{id}', name: 'manageAdmin_upgradeUser')]
    public function manageAdminUpgraseUser(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $session = $request->getSession();
        $userisLogin = $session->get('userisLogin');
        $isadmin = $session->get('isadmin');
        if($userisLogin != '1' && $isadmin != '1'){
            return $this->json([]);
        }
        $userforUpdate = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $userforUpdate->setRole('admin');
        $entityManager->flush();
        return $this->json(["status" => 1]);
    }
    #[Route('/manageAdmin/downgradeUser/{id}', name: 'manageAdmin_downgradeUser')]
    public function manageAdminDowngradeUser(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $session = $request->getSession();
        $userisLogin = $session->get('userisLogin');
        $isadmin = $session->get('isadmin');
        if($userisLogin != '1' && $isadmin != '1'){
            return $this->json([]);
        }
        $userforUpdate = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $userforUpdate->setRole('user');
        $entityManager->flush();
        return $this->json(["status" => 1]);
    }
}