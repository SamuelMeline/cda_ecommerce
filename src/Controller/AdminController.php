<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/update-user/{id}', name: 'admin_update_user', methods: ['POST'])]
    public function updateUser(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Récupérer les données soumises du formulaire
        $email = $request->request->get('email');
        $role = $request->request->get('role');

        // Mettre à jour les champs de l'utilisateur
        $user->setEmail($email);
        $user->setRoles([$role]);

        // Persistez les modifications dans la base de données
        $entityManager->flush();

        // Rediriger l'utilisateur vers la page d'administration
        return $this->redirectToRoute('admin_index');
    }
}
