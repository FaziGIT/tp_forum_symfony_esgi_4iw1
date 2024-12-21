<?php

namespace App\Controller\Api;

use App\Entity\Topic;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TopicController extends AbstractController
{
    private TopicRepository $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    #[Route('/api/topics/search', name: 'api_topic_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        // Récupérer le paramètre 'query' de la requête
        $query = $request->query->get('query', '');

        if (empty($query)) {
            return new JsonResponse(['message' => 'Aucune recherche spécifiée'], Response::HTTP_BAD_REQUEST);
        }

        // Effectuer la recherche sur le titre et la description des topics
        $topics = $this->topicRepository->createQueryBuilder('t')
            ->where('t.title LIKE :query OR t.description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();

        // Formater les résultats
        $data = array_map(function (Topic $topic) {
            return [
                'id' => $topic->getId(),
                'title' => $topic->getTitle(),
                'description' => $topic->getDescription(),
            ];
        }, $topics);

        return new JsonResponse($data);
    }
}
