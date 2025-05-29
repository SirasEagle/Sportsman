<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductNewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/product', name: 'index_product')]
    public function index(): Response
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/new', name: 'new_product')]
    public function newUser(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductNewType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Werte aus dem Formular holen und in das User-Objekt schreiben
            $product = $form->getData();

            // Das User-Objekt in der Datenbank persistieren
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('index_product');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
