<?php
// src/Controller/main.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class main
{
    #[Route('/')]
    public function main(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.' <br> hello world</body></html>'
        );
    }
}