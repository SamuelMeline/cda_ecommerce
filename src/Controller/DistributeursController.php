<?php

namespace App\Controller;

use App\Entity\Distributeurs;
use App\Form\DistributeursType;
use App\Repository\DistributeursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/distributeurs')]
class DistributeursController extends AbstractController
{
    #[Route('/', name: 'app_distributeurs_index', methods: ['GET'])]
    public function index(DistributeursRepository $distributeursRepository): Response
    {
        return $this->render('distributeurs/index.html.twig', [
            'distributeurs' => $distributeursRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_distributeurs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $distributeur = new Distributeurs();
        $form = $this->createForm(DistributeursType::class, $distributeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($distributeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_distributeurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('distributeurs/new.html.twig', [
            'distributeur' => $distributeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_distributeurs_show', methods: ['GET'])]
    public function show(Distributeurs $distributeur): Response
    {
        return $this->render('distributeurs/show.html.twig', [
            'distributeur' => $distributeur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_distributeurs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Distributeurs $distributeur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DistributeursType::class, $distributeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_distributeurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('distributeurs/edit.html.twig', [
            'distributeur' => $distributeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_distributeurs_delete', methods: ['POST'])]
    public function delete(Request $request, Distributeurs $distributeur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$distributeur->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($distributeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_distributeurs_index', [], Response::HTTP_SEE_OTHER);
    }
}
