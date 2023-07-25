<?php
// src/Controller/BlogController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    public function list(): Response
    {
        return new Response('<html><body>Here is a list of blogs...</body></html>');
    }
    
    #[Route('/blog/{slug}', name: 'blog_show')]
    public function show(): Response
    {
        return new Response('<html><body>Here is an object of a blog...</body></html>');
    }
}