<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Topic;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/response')]
final class CommentController extends AbstractController
{
    #[Route('/topic/{id}/comment', name: 'app_comment_create', methods: ['POST'])]
    public function createComment(Request $request, $id, HubInterface $hub, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);
        if (!empty($data['content'])) {
            $content = $data['content'];

            $user = $this->getUser();
            $topic = $entityManager->getRepository(Topic::class)->find($id);

            if (!$topic) {
                return new JsonResponse(['error' => 'Topic not found'], 404);
            }

            $comment = new Comment();
            $comment->setContent($content);
            $comment->setUser($user);
            $comment->setTopic($topic);

            // Sauvegarder le commentaire
            $entityManager->persist($comment);
            $entityManager->flush();

            // Publier un événement Mercure
            $update = new Update(
                'https://example.com/topics/' . $id, // L'URL à laquelle les utilisateurs sont abonnés
                json_encode([
                    'user' => $user->getUsername(),
                    'content' => $comment->getContent(),
                    'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
                ])
            );
            $hub->publish($update);

            // Retourner la réponse JSON
            return new JsonResponse([
                'success' => true,
                'comment' => [
                    'user' => ['username' => $user->getUsername()],
                    'content' => $comment->getContent(),
                    'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
                ],
            ]);
        }

        return new JsonResponse(['error' => 'Content is required'], 400);
    }

    #[Route('/{id}', name: 'app_response_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $response, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $response->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($response);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_topic_show', ['id' => $response->getTopic()->getId()], Response::HTTP_SEE_OTHER);
    }
}
