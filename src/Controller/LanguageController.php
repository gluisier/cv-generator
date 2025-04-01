<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\Language1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/language')]
class LanguageController extends AbstractController
{
    #[Route('/', name: 'language_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $languages = $entityManager
            ->getRepository(Language::class)
            ->findAll();

        return $this->render('language/index.html.twig', [
            'languages' => $languages,
        ]);
    }

    #[Route('/new', name: 'language_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $language = new Language();
        $form = $this->createForm(Language1Type::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($language);
            $entityManager->flush();

            return $this->redirectToRoute('language_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('language/new.html.twig', [
            'language' => $language,
            'form' => $form,
        ]);
    }

    #[Route('/{code}', name: 'language_show', methods: ['GET'])]
    public function show(Language $language): Response
    {
        return $this->render('language/show.html.twig', [
            'language' => $language,
        ]);
    }

    #[Route('/{code}/edit', name: 'language_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity] Language $language, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Language1Type::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('language_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('language/edit.html.twig', [
            'language' => $language,
            'form' => $form,
        ]);
    }

    #[Route('/{code}', name: 'language_delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity] Language $language, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$language->getCode(), $request->request->get('_token'))) {
            $entityManager->remove($language);
            $entityManager->flush();
        }

        return $this->redirectToRoute('language_index', [], Response::HTTP_SEE_OTHER);
    }
}
