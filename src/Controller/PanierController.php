<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/ajouter-au-panier/{id}', name: 'app_panier')]
    public function ajouterAuPanier(Produits $produits, SessionInterface $session): Response
    {
        $produit_id = $produits->getId();
        $panier = $session->get('panier', []);

        if (!empty($panier[$produit_id])) {
            $panier[$produit_id]++;
        } else {
            $panier[$produit_id] = 1;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_afficher_panier');
    }

    #[Route('/afficher-panier', name: 'app_afficher_panier')]
    public function afficherPanier(SessionInterface $session, ProduitsRepository $produitsRepository): Response
    {
        $panier = $session->get('panier', []);

        $commande = [];

        $total = 0;

        foreach ($panier as $id => $quantity) {
            $produit = $produitsRepository->find($id);
            $totalItem = $produit->getPrice() * $quantity;
            $total += $totalItem;

            $commande[] = [
                'produit' => $produit,
                'quantity' => $quantity,
                'totalItem' => $totalItem
            ];
        }

        return $this->render('panier/afficher_panier.html.twig', [
            'panier' => $commande,
            'total' => $total
        ]);
    }

    #[Route('/ajouter-quantite-panier/{id}', name: 'app_ajouter_quantite_panier')]
    public function ajouterQuantitePanier(Produits $produits, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        $produit_id = $produits->getId();

        if (!empty($panier[$produit_id])) {
            $panier[$produit_id]++;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_afficher_panier');
    }

    #[Route('/supprimer-du-panier/{id}', name: 'app_supprimer_quantite_panier')]
    public function supprimerDuPanier(Produits $produits, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        $produit_id = $produits->getId();

        if (!empty($panier[$produit_id])) {
            if($panier[$produit_id] > 1) {
                $panier[$produit_id]--;
            } else {
                unset($panier[$produit_id]);
            }
        }

        $session->set('panier', $panier);

        $this -> addFlash('success', 'Produit supprimé du panier');

        return $this->redirectToRoute('app_afficher_panier');
    }

    #[Route('/vider-panier', name: 'app_vider_panier')]
    public function viderPanier(SessionInterface $session): Response
    {
        $session->set('panier', []);

        $this -> addFlash('success', 'Panier vidé');

        return $this->redirectToRoute('app_afficher_panier');
    }
}
