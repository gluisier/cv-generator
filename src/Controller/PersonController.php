<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\AbbreviationRepository;
use App\Repository\PersonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route("/", host: "%host%", name: "me", methods: ["GET"])]
    #[Route("/", host: "{_locale}.%host%", name: "me_translated", methods: ["GET"])]
    public function me(PersonRepository $personRepository, AbbreviationRepository $abbreviationRepository): Response
    {
        $response = new Response('', Response::HTTP_OK/*, ['Content-Type' => 'application/xhtml+xml']*/);
        return $this->render('cv.html.twig', [
            'me' => $personRepository->find($this->getParameter('me')),
            'abbreviations' => $abbreviationRepository->findAll(),
            'action' => 'show',
        ], $response);
    }

    #[Route("/edit", host: "%host%", name: "me_edit", methods: ["GET"])]
    #[Route("/", host: "{_locale}.%host%", name: "me_edit_translated", methods: ["GET"])]
    public function meEdit(PersonRepository $personRepository, AbbreviationRepository $abbreviationRepository): Response
    {
        $response = new Response('', Response::HTTP_OK, ['Content-Type' => 'application/xhtml+xml']);
        return $this->render('cv.html.twig', [
            'me' => $personRepository->find($this->getParameter('me')),
            'abbreviations' => $abbreviationRepository->findAll(),
            'action' => 'edit',
        ]);
    }

    #[Route("/person/", name: "person_index", methods: ["GET"])]
    public function index(PersonRepository $personRepository): Response
    {
        return $this->render('person/index.html.twig', [
            'people' => $personRepository->findAll(),
        ]);
    }

    #[Route("/person/new", name: "person_new", methods: ["GET","POST"])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('person_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('person/new.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route("/{id}", name: "person_show", methods: ["GET"])]
    public function show(#[MapEntity] Person $person): Response
    {
        return $this->render('person/show.html.twig', [
            'person' => $person,
        ]);
    }

    #[Route("/person/{id}/edit", name: "person_edit", methods: ["GET","POST"])]
    public function edit(Request $request, #[MapEntity] Person $person, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('person_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route("/person/{id}", name: "person_delete", methods: ["POST"])]
    public function delete(Request $request, #[MapEntity] Person $person, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($person);
            $entityManager->flush();
        }

        return $this->redirectToRoute('person_index', [], Response::HTTP_SEE_OTHER);
    }
}
