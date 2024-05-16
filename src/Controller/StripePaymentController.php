<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Commandes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripePaymentController extends AbstractController
{
    #[Route('/commande/creer-session-stripe/{numero_cmd}', name: 'app_paiement_stripe')]
    public function stripeCheckout(
        string $numero_cmd,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
    ): RedirectResponse {
        // Tableau vide de la commande
        $produits_stripe = [];
        // Récupération de la commande a l'aide de son numéro unique
        $commandes = $em->getRepository(Commandes::class)->findOneBy(['numero_cmd' => $numero_cmd]);


        // Si la commande n'existe pas
        if (!$commandes) {
            $this->addFlash('danger', 'Commande introuvable');
            return $this->redirectToRoute('app_produits_index');
        }

        foreach ($commandes->getCommandeDetails() as $produit) {
            $produits_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $produit->getProduits()->getName(),
                    ],
                    'unit_amount' => $produit->getProduits()->getPrice() * 100,
                ],
                'quantity' => $produit->getQuantite(),
            ];
        }

        // Création de la session stripe
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        header('Content-Type: application/json');

        $checkout_session = \Stripe\Checkout\Session::create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'payment_method_types' => ['card'],
            'line_items' => $produits_stripe,
            'mode' => 'payment',
            'client_reference_id' => $numero_cmd,
            'success_url' => $urlGenerator->generate('app_paiement_success_stripe', ['numero_cmd' => $commandes->getNumeroCmd()], UrlGenerator::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('app_paiement_cancel_stripe', ['numero_cmd' => $commandes->getNumeroCmd()], UrlGenerator::ABSOLUTE_URL),
        ]);

        return $this->redirect($checkout_session->url);
    }

    #[Route('/commande/success-stripe/{numero_cmd}', name: 'app_paiement_success_stripe')]
    public function stripeSuccessPaiement(
        Request $request,
        EntityManagerInterface $em,
        Commandes $commandes
    ): Response
    {
        $em->remove($commandes);
        $em->flush();
        return $this->render('stripe/success.html.twig');
    }

    #[Route('/commande/cancel-stripe/{numero_cmd}', name: 'app_paiement_cancel_stripe')]
    public function stripeCancelPaiement(): Response
    {
        return $this->render('stripe/cancel.html.twig');
    }
}
