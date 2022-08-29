<?php

namespace App\Controller;

use App\Document\User;
use App\Form\UserType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('user')]
class UserController extends AbstractController
{
    #[Route('/list', name: 'app_user_list', methods: ['GET'])]
    public function index(DocumentManager $documentManager): JsonResponse
    {
        $users = $documentManager->getRepostory(User::class)->find();

        return $this->json($users);
    }

    #[Route('/{id}', name: 'app_user_find', methods: ['GET'])]
    public function find( User $user = null): JsonResponse
    {
        if (!$user){
            return $this->json(null, 404);
        }

        return $this->json($user);
    }

    #[Route('/new', name: 'app_user_new', methods: ['POST'])]
    public function create(Request $request, DocumentManager $documentManager): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->toArray());
        if ($form->isSubmitted() && $form->isValid()){
            $documentManager->persist($user);
            $documentManager->flush();

            return $this->json($user, 200);
        }

        $errors = $form->getErrors(true);
        $msg = [];
        foreach ($errors as $error) {
            $msg[] = $error->getMessage();
        }

        return $this->json($msg, 400);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['PUT'])]
    public function edit(User $user = null ,Request $request, DocumentManager $documentManager): JsonResponse
    {
        if (!$user){
            return $this->json(null, 404);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->toArray());
        if ($form->isSubmitted() && $form->isValid()){
            $documentManager->persist($user);
            $documentManager->flush();

            return $this->json($user, 201);
        }

        $errors = $form->getErrors(true);
        $msg = [];
        foreach ($errors as $error){
            $msg[] = $error->getMessage();
        }

        return $this->json($msg, 400);
    }

    #[Route('/{id}', name: 'app_user_remove', methods: ['DELETE'])]
    public function remove( User $user = null, DocumentManager $documentManager): JsonResponse
    {
        if (!$user){
            return $this->json(null, 404);
        }

        $documentManager->remove($user);
        $documentManager->flush();

        return $this->json(null);
    }
}
