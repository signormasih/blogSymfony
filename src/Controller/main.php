<?php
// src/Controller/main.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class main extends AbstractController
{
    #[Route('/', name: 'main')]
    public function main(): Response
    {
        $number = random_int(0, 100);
        $msg = 'Hello World';

        return $this->render('main.html.twig', [
            'number' => $number,
            'message' => $msg
        ]);

        // return new Response(
        //     '<html><body>Lucky number: '.$number.' <br> hello world</body></html>'
        // );
    }
    #[Route('/blog', name: 'blog', /* methods: ['GET'] */)]
    public function blog(): Response
    {
        $msg = 'this is blog page';

        return $this->render('blog.html.twig', [
            'message' => $msg
        ]);
    }

    // #[Route('/post/{postTitle}', name: 'post', requirements: ['page' => '\d+'])]
    // #[Route('/post/{page<\d+>}', name: 'post')]
    #[Route('/post/{postTitle}', name: 'post')]
    public function postBlog(string $postTitle): Response
    {
        $msg = 'this is blog page'.$postTitle;

        return $this->render('blog.html.twig', [
            'message' => $msg
        ]);
    }
}