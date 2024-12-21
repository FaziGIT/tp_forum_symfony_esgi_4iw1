<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Topic;
use App\Form\CommentType;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/topics')]
final class TopicController extends AbstractController
{
    #[Route(name: 'app_topic_index', methods: ['GET'])]
    public function index(TopicRepository $topicRepository): Response
    {
        return $this->render('topic/index.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_topic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $topic->setUser($this->getUser());
            $topic->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_topic_show', methods: ['GET', 'POST'])]
    public function show(Topic $topic, Request $request, EntityManagerInterface $entityManager, TopicRepository $topicRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $newTopic = $topicRepository->findInOneRequest($topic);
        return $this->render('topic/show.html.twig', [
            'topic' => $newTopic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_topic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        if ($topic->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_topic_delete', methods: ['POST'])]
    public function delete(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $topic->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
    }
}
