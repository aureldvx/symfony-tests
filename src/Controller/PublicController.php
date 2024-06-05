<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\DefaultType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PublicController extends AbstractController
{
    #[Route(path: '/', name: 'homepage', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route(path: '/about', name: 'about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route(path: '/redir', name: 'redir', methods: ['GET'])]
    public function redir(): Response
    {
        return $this->redirectToRoute('about');
    }

    #[Route(path: '/form', name: 'form', methods: ['GET', 'POST'])]
    public function handleForm(Request $request): Response
    {
        $form = $this->createForm(DefaultType::class)->handleRequest($request);

        if ($form->isSubmitted()) {
            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('lastname')->getData();

            if ($firstname === 'salut' && $lastname === 'salut') {
                return $this->redirectToRoute('homepage');
            }

            $this->addFlash('error', 'Les valeurs ne sont pas valides');
        }

        return $this->render('form.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route(path: '/authenticated', name: 'authenticated', methods: ['GET'])]
    public function authenticated(): Response
    {
        return $this->render('authenticated.html.twig');
    }
}