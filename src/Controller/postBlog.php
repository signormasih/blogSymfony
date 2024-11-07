<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\PostType;
use App\Entity\Users;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Psr\Log\LoggerInterface;



class postBlog extends AbstractController
{
    #[Route('/createPost', name: 'createPost')]
    public function createPost(EntityManagerInterface $entityManager, Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/fileMedia')] string $fileMediaDirectory,
        LoggerInterface $logger): Response
    {
        $session = $request->getSession();
        $userisLogin = $session->get('userisLogin');
        $username_User = $session->get('username');
        if($userisLogin != '1'){
            return $this->redirect('/');
        }

        $repository_category = $entityManager->getRepository(Category::class);
        $allCatgory = $repository_category->findAll();

        $repository_PostType = $entityManager->getRepository(PostType::class);
        $allPostType = $repository_PostType->findAll();

        if ($request->isMethod('post')){
            $title = $request->get('title');
            $name = $request->get('name');
            $content = $request->get('content');
            $category = $request->get('category');
            $postType = $request->get('postType');
            $fileMedia = $request->files->get('media');

            if($title == "" || $name == "" || $content == ""){
                return $this->render('postBlog.html.twig', [
                    'allCategory' => $allCatgory,
                    'allPostType' => $allPostType
                ]);
            }

            $userData = $entityManager->getRepository(Users::class)->findOneBy(['username' => $username_User]);

            $post = new Post();
            //$logger->info('arh => '.$fileMedia);
            if ($fileMedia) {
                $originalFilename = pathinfo($fileMedia->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fileMedia->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fileMedia->move($fileMediaDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setMedia($newFilename);
            }

            $post->setTitle($title);
            $post->setName($name);
            $post->setContent($content);
            $post->setCategoryId($category);
            $post->setPostTypeId($postType);
            $post->setUserId($userData->getId());
            $currentDateTime = new DateTime();
            $post->setPublishedAt($currentDateTime);
            $post->setStatus("process");
            //process
            //reject
            //valid

            $entityManager->persist($post);
            $entityManager->flush();
            
            return $this->redirect('/blog');
        }

        return $this->render('postBlog.html.twig', [
            'allCategory' => $allCatgory,
            'allPostType' => $allPostType
        ]);
        
    }
}