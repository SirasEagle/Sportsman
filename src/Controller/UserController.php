<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserNewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user', name: 'index_user')]
    public function index(): Response
    {
        // check if a user exists
        $userRepository = $this->entityManager->getRepository(User::class);
        $anyUser = $userRepository->findOneBy([]);
        if (!$anyUser) {
            // no user exists, create new user
            return $this->redirectToRoute('new_user');
        }

        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/new', name: 'new_user')]
    public function newUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserNewType::class, $user);
        $form->handleRequest($request);
        // FIXME:

        if ($form->isSubmitted() && $form->isValid()) {
            // Werte aus dem Formular holen und in das User-Objekt schreiben
            $user = $form->getData();

            // Das User-Objekt in der Datenbank persistieren
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // TODO: redirect to user show
            // TODO: make multiple users possible
            // return $this->redirectToRoute('show_exercise', ['id' => $exercise->getId()]);
            return $this->redirectToRoute('index_user');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
