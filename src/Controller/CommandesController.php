<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Entity\Commandes;
use App\Entity\CommandeDetails;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProduitsRepository;
use App\Repository\CommandesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeDetailsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandesController extends AbstractController
{

    #[Route('/commandes', name: 'app_valider_commandes')]
    public function ajouterCommande(
        SessionInterface $session,
        ProduitsRepository $produitsRepository,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $panier = $session->get('panier', []);
        $commande = new Commandes();
        $commande->setUser($this->getUser());
        $commande->setNumeroCmd(uniqid());
        $total = 0;

        foreach ($panier as $item => $quantity) {
            $commande_details = new CommandeDetails();
            $produit = $produitsRepository->find($item);
            $prix = $produit->getPrice();
            $commande_details->setProduits($produit);
            $commande_details->setPrix($prix);
            $commande_details->setQuantite($quantity);
            $commande->addCommandeDetail($commande_details);
        }

        $em->persist($commande);
        $em->flush();

        $session->remove('panier');
        $this->addFlash('success', 'Votre commande a été validée avec succès');
        return $this->redirectToRoute('app_resume_commande');
    }

    #[Route('/resume-commandes', name: 'app_resume_commande')]
    public function resumeCommande(
        CommandesRepository $commandesRepository,
        CommandeDetailsRepository $commandeDetailsRepository
    ): Response {

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        // Récupérer les commandes de l'utilisateur connecté
        $commandes = $commandesRepository->findBy(['user' => $user]);
        // Tableau des détails des commandes;
        $details_commande = [];
        // Initialisation du total
        $total = 0;

        // Parcourir les commandes
        foreach ($commandes as $commande) {
            $details = $commandeDetailsRepository->findBy(['commandes' => $commande]);
            // Ajouter les détails de la commande dans le tableau
            $details_commande[$commande->getId()] = $details;

            // Calculer le total
            foreach ($details as $detail) {
                $total += $detail->getPrix() * $detail->getQuantite();
            }
        }

        return $this->render('commandes/resume_commande.html.twig', [
            'commandes' => $commandes,
            'details_commande' => $details_commande,
            'total' => $total
        ]);
    }

    #[Route('/supprimer-commande/{id}', name: 'app_supprimer_commande', methods: ['POST'])]
    public function supprimerCommande(
        Request $request,
        Commandes $commandes,
        EntityManagerInterface $em
    ) {
        if ($this->isCsrfTokenValid('delete' . $commandes->getId(), $request->request->get('_token'))) {
            $em->remove($commandes);
            $em->flush();
            $this->addFlash('success', 'Commande supprimée avec succès');
        }
        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }
}
