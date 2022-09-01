<?php

namespace App\Controller;

use App\Document\User;
use App\Form\UserType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('user')]
class UserController extends AbstractController
{
    #[Route('/list', name: 'app_user_list', methods: ['GET'])]
    public function index(DocumentManager $documentManager): JsonResponse
    {
        $users = $documentManager->getRepository(User::class)->findAll();

        return $this->json($users);
    }

    #[Route('/{id}', name: 'app_user_find', methods: ['GET'])]
    #[ParamConverter("User", class: "App\Document\User")]
    public function find(User $user): JsonResponse
    {
        return $this->json($user);
    }

    /**
     * @throws MongoDBException
     */
    #[Route('/new', name: 'app_user_new', methods: ['POST'])]
    public function create(Request $request, DocumentManager $documentManager): JsonResponse
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $documentManager->persist($user);
            $documentManager->flush();

            return $this->json($user, Response::HTTP_CREATED);
        }

        $errors = $form->getErrors(true);
        $msg = [];

        foreach ($errors as $error) {
            $msg[] = $error->getMessage();
        }

        return $this->json($msg, Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['PUT'])]
    public function edit(User $user, Request $request, DocumentManager $documentManager): JsonResponse
    {
        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->toArray());
        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->persist($user);
            $documentManager->flush();

            return $this->json($user, 201);
        }

        $errors = $form->getErrors(true);
        $msg = [];
        foreach ($errors as $error) {
            $msg[] = $error->getMessage();
        }

        return $this->json($msg, 400);
    }

    #[Route('/{id}', name: 'app_user_remove', methods: ['DELETE'])]
    public function remove(User $user, DocumentManager $documentManager): JsonResponse
    {
        $documentManager->remove($user);
        $documentManager->flush();

        return $this->json(null);
    }
}
