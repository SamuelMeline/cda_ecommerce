<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Entity\Commentaires;
use App\Entity\Distributeurs;
use App\Form\CommentairesType;
use Doctrine\ORM\EntityManager;
use App\Services\SimpleUploadService;
use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentairesRepository;
use App\Repository\DistributeursRepository;
use App\Repository\PhotosRepository;
use App\Security\Voter\ProduitsVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/produits')]
class ProduitsController extends AbstractController
{
    private $security;
    private $imagesDirectory;

    public function __construct(Security $security, ParameterBagInterface $parameterBag)
    {
        $this->security = $security;
        $this->imagesDirectory = $parameterBag->get('images_directory');
    }

    #[Route('/', name: 'app_produits_index', methods: ['GET'])]
    public function index(
        ProduitsRepository $produitsRepository,
        CategoriesRepository $categoriesRepository,
        DistributeursRepository $distributeursRepository
    ): Response {
        $user = $this->getUser();
        return $this->render('produits/index.html.twig', [
            'produits' => $produitsRepository->findBy(['user' => $user]),
            // 'produits' => $produitsRepository->findAll(),
            'categories' => $categoriesRepository->findAll(),
            'distributeurs' => $distributeursRepository->findAll()
            // dd($produitsRepository->findBy(['user' => $user]))
        ]);
    }

    #[Route('/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SimpleUploadService $simpleUploadService): Response
    {
        $user = $this->security->getUser();
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photos = $request->files->all();

            if ($photos != null) {
                $images = $photos['produits']['photos'];

                foreach ($images as $image) {
                    $new_photos = new Photos();
                    $image_name = $image['name'];
                    $new_photo = $simpleUploadService->upload($image_name);
                    $new_photos->setName($new_photo);
                    $produit->addPhoto($new_photos);

                    $entityManager->persist($new_photos);
                    $entityManager->flush();

                    $this->addFlash('success', 'Photo ajouté avec succès');
                }

                $produit->setUser($user);
                $entityManager->persist($produit);
                $entityManager->flush();

                $this->addFlash('success', 'Produit ajouté avec succès');
                return $this->redirectToRoute('app_produits_index');
            } else {
                $this->addFlash('error', 'Veuillez ajouter une image');
                return $this->redirectToRoute('app_produits_new');
            }
        }

        return $this->render('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/details-produit/{id}', name: 'app_produits_show', methods: ['GET'])]
    public function show(Produits $produit): Response
    {
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[IsGranted(ProduitsVoter::EDIT, subject: 'produit')]
    #[Route('/{id}/edit', name: 'app_produits_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produits $produit, EntityManagerInterface $entityManager, SimpleUploadService $simpleUploadService): Response
    {
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photos = $request->files->all();

            if ($photos != null) {
                $images = $photos['produits']['photos'];

                foreach ($images as $image) {
                    $new_photos = new Photos();
                    $image_name = $image['name'];
                    $new_photo = $simpleUploadService->upload($image_name);
                    $new_photos->setName($new_photo);
                    $produit->addPhoto($new_photos);

                    $entityManager->persist($new_photos);
                    $entityManager->flush();
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/produit/{id}/supprimer-image/{imageID}', name: 'app_produits_supprimer_image')]
    public function supprimerImage(Produits $produit, int $imageID, EntityManagerInterface $entityManager, PhotosRepository $photosRepository): Response
    {
        $photo_id = $photosRepository->find($imageID);
        if ($photo_id && $photo_id->getProduits() === $produit) {
            $entityManager->remove($photo_id);
            $entityManager->flush();
            $this->addFlash('success', 'Image supprimée avec succès');
            return $this->redirectToRoute('app_produits_edit', ['id' => $produit->getId()]);
        } else {
            $this->addFlash('error', 'Image non trouvée');
            return $this->redirectToRoute('app_produits_edit', ['id' => $produit->getId()]);
        }
    }

    #[Route('/supprimer-produit/{id}', name: 'app_produits_delete', methods: ['POST'])]
    public function delete(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/categorie-produit', name: 'app_produits_categorie', methods: ['POST'])]
    public function produitsCategorie(Request $request, ProduitsRepository $produitsRepository, CategoriesRepository $categoriesRepository, DistributeursRepository $distributeursRepository): Response
    {
        $categorie_id = $request->request->get('categorie_id');
        $categories = $categoriesRepository->findAll();
        $distributeurs = $distributeursRepository->findAll();

        $categorie_id = $request->request->get('categorie_id');
        // dd($categorie_id);
        return $this->render('produits/index.html.twig', [
            'categories' => $categories,
            'selected_categorie' => $categorie_id,
            'produits' => $produitsRepository->findByCategorie($categorie_id),
            'distributeurs' => $distributeurs
        ]);
    }

    #[Route('/distributeur-produit', name: 'app_produits_distributeur', methods: ['POST'])]
    public function produitsDistributeur(Request $request, ProduitsRepository $produitsRepository, CategoriesRepository $categoriesRepository, DistributeursRepository $distributeursRepository): Response
    {
        $distributeur_id = $request->request->get('distributeur_id');
        $distributeurs = $distributeursRepository->findAll();
        $categories = $categoriesRepository->findAll();

        return $this->render('produits/index.html.twig', [
            'distributeurs' => $distributeurs,
            'selected_distributeur' => $distributeur_id,
            'produits' => $produitsRepository->produitsByDistributeur($distributeur_id),
            'categories' => $categories
        ]);
    }

    #[Route('/comments-produit/{id}', name: 'app_comments_produit')]
    public function detailsProduit(Produits $produit, Request $request, EntityManagerInterface $em, CommentairesRepository $commentairesRepository): Response
    {
        $commentaire = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setProduits($produit);
            $produit->addCommentaire($commentaire);
            $em->persist($commentaire);
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

        return $this->render('produits/comments_produit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
            'commentaires' => $commentairesRepository->findBy(['produits' => $produit])
        ]);
    }
}
