<?php

namespace App\Controller;

use App\Entity\Technology;
use App\Form\TechnologyType;
use App\Repository\TechnologyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/technology')]
class TechnologyController extends AbstractController
{
    #[Route('/', name: 'technology_index', methods: ['GET'])]
    public function index(TechnologyRepository $technologyRepository): Response
    {
        return $this->render('technology/index.html.twig', [
            'technologies' => $technologyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'technology_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $technology = new Technology();
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($technology);
            $entityManager->flush();

            return $this->redirectToRoute('technology_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('technology/new.html.twig', [
            'technology' => $technology,
            'form' => $form,
        ]);
    }

    #[Route('/{realisation}/{skill}/{version?null}', name: 'technology_show', methods: ['GET'], requirements: ['version' => '[^/]*'])]
    public function show(#[MapEntity] Technology $technology): Response
    {
        return $this->render('technology/show.html.twig', [
            'technology' => $technology,
        ]);
    }

    #[Route('/{realisation}/{skill}/{version?null}/edit', name: 'technology_edit', methods: ['GET', 'POST'], requirements: ['version' => '[^/]*'])]
    public function edit(Request $request, #[MapEntity] Technology $technology, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('technology_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('technology/edit.html.twig', [
            'technology' => $technology,
            'form' => $form,
        ]);
    }

    #[Route('/{realisation}/{skill}/{version?null}', name: 'technology_delete', methods: ['POST'], requirements: ['version' => '[^/]*'])]
    public function delete(Request $request, #[MapEntity] Technology $technology, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$technology->getVersion(), $request->request->get('_token'))) {
            $entityManager->remove($technology);
            $entityManager->flush();
        }

        return $this->redirectToRoute('technology_index', [], Response::HTTP_SEE_OTHER);
    }
}
