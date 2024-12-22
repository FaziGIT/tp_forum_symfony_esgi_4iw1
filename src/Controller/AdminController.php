<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAllWithoutAdmin();
        return $this->render('admin/index.html.twig', [
            'users' => $user,
        ]);
    }

    #[Route('/admin/ban/{id}', name: 'app_admin_ban', methods: ['POST'])]
    public function switchBan(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            if (in_array('ROLE_BANNED', $user->getRoles())) {
                $user->setRoles(['ROLE_USER']);
            } else {
                $user->setRoles(['ROLE_BANNED']);
            }
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
    }
}
