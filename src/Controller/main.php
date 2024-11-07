<?php
// src/Controller/main.php
namespace App\Controller;


use App\Entity\Category;
use App\Entity\PostType;
use App\Entity\User;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

class main extends AbstractController
{
    #[Route('/', name: 'main')]
    public function main(): Response
    {

        return $this->render('main.html.twig', []);
        // return new Response(
        //     '<html><body>Lucky number: '.$number.' <br> hello world</body></html>'
        // );
    }
    #[Route('/blog', name: 'blog', /* methods: ['GET'] */)]
    public function blog(EntityManagerInterface $entityManager, Request $request): Response
    {
        $allcategory = "";
        $repository_category = $entityManager->getRepository(Category::class);
        $allCatgory = $repository_category->findAll();

        $repository_PostType = $entityManager->getRepository(PostType::class);
        $allPostType = $repository_PostType->findAll();

        return $this->render('blog.html.twig', [
            'allcategory' => $allCatgory,
            'allPostType' => $allPostType
        ]);
    }
    #[Route('/blog/data', name: 'blog_data')]
    public function blogData(EntityManagerInterface $entityManager, Request $request, LoggerInterface $logger): JsonResponse
    {
        $session = $request->getSession();
        $userisLogin = $session->get('userisLogin');
        $username_User = $session->get('username');
        $isadmin = $session->get('isadmin');
        
        $dataJson = [];

        $repo_post = $entityManager->getRepository(Post::class);
        $repo_category = $entityManager->getRepository(Category::class);
        $repo_posttype = $entityManager->getRepository(PostType::class);
        $repo_User = $entityManager->getRepository(User::class);
        
        if($username_User){
            $user_data = $repo_User->findOneBy(["username" => $username_User]);
            $userId = $user_data->getId();
        }else{
            $userId = 0;
        }
        //with filter
        if($request->isMethod('post')){
            // $postData = $entityManager->getRepository(Post::class)->findValidPostAndUserPost($userId);
            return $this->json($dataJson);
        }
        
        //without filter
        if($isadmin == '1'){
            $allPost= $repo_post->findAll();
            $row = 0;
            foreach($allPost as $data){
                $dataJson[$row]['id'] = $data->getId();
                $dataJson[$row]['title'] = $data->getTitle();
                $dataJson[$row]['name'] = $data->getName();
                $dataJson[$row]['publishedAt'] = $data->getPublishedAt();

                $category_post = $repo_category->findOneBy(["id" => $data->getCategoryId()]);
                if($category_post){
                    $categoryName = $category_post->getName();
                }
                $dataJson[$row]['category'] = $categoryName;

                $postType_post = $repo_posttype->findOneBy(['id' => $data->getPostTypeId()]);
                if($postType_post){
                    $postTypeName = $postType_post->getName();
                }
                $dataJson[$row]['posttype'] = $postTypeName;

                $user_creator = $repo_User->findOneBy(["id" => $data->getUserId()]); 
                if($user_creator){
                    $nameCreator = $user_creator->getUsername();
                }
                $dataJson[$row]['creator'] = $user_creator->getUsername();
                $row++;
            }
            $dataJson['count'] = $row;
        }else{
            $postData = $entityManager->getRepository(Post::class)->findValidPostAndUserPost($userId);
            // $logger->info("arh 77 ==> ". print_r($postData[0],1 ));
            // $postData = [];
            $row = 0;
            foreach($postData as $data){
                $dataJson[$row]['id'] = $data['id'];
                $dataJson[$row]['title'] = $data['title'];
                $dataJson[$row]['name'] = $data['name'];
                $dataJson[$row]['publishedAt'] = $data['published_at'];

                $category_post = $repo_category->findOneBy(["id" => $data["category_id"]]);
                if($category_post){
                    $categoryName = $category_post->getName();
                }
                $dataJson[$row]['category'] = $categoryName;

                $postType_post = $repo_posttype->findOneBy(['id' => $data["post_type_id"]]);
                if($postType_post){
                    $postTypeName = $postType_post->getName();
                }
                $dataJson[$row]['posttype'] = $postTypeName;

                $user_creator = $repo_User->findOneBy(["id" => $data['user_id']]); 
                $dataJson[$row]['creator'] = $user_creator->getUsername();
                $row++;
            }
            $dataJson['count'] = $row;
        }

        return $this->json($dataJson);
    }

    // #[Route('/post/{postTitle}', name: 'post', requirements: ['page' => '\d+'])]
    // #[Route('/post/{page<\d+>}', name: 'post')]
    #[Route('/post/{id}', name: 'singlePost')]
    public function postBlog(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $session = $request->getSession();
        $username_User = $session->get('username');
        $isadmin = $session->get('isadmin');

        $repo_post = $entityManager->getRepository(Post::class);

        if($isadmin == '1'){
            $postData = $repo_post->find($id);
        }else{
            $postData = $repo_post->findOneBy(['id' => $id]);
        }
        
        return $this->render('singleBlog.html.twig', [
            'postData' => $postData,
            'isadmin' => $isadmin
        ]);
    }
    #[Route('/deletePost/{id}', name: 'deletePost')]
    public function manageAdminDeletePostType(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $session = $request->getSession();
        $userisLogin = $session->get('userisLogin');
        $isadmin = $session->get('isadmin');
        if($userisLogin != '1' && $isadmin != '1'){
            return $this->json([]);
        }
        $postType = $entityManager->getRepository(Post::class)->findOneBy(['id' => $id]);
        $entityManager->remove($postType);
        $entityManager->flush();
        return $this->json(["status" => 1]);
    }
    #[Route('/statuspost/{id}', name: 'statusPost')]
    public function manageAdminInsertCategory(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $session = $request->getSession();
        $userisLogin = $session->get('userisLogin');
        $isadmin = $session->get('isadmin');
        if($userisLogin != '1' && $isadmin != '1'){
            return $this->json([]);
        }
        $dataStatus = $request->get('status');
        if($dataStatus == ""){
            return $this->json(["status" => 0]);
        }
        $post = $entityManager->getRepository(Post::class)->find($id);
        $post->setStatus($dataStatus);
        $entityManager->flush();

        return $this->json(["status" => 1]);
    }
}