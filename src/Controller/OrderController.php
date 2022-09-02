<?php

namespace App\Controller;

use App\Document\Order;
use App\Document\User;
use App\Form\OrderType;
use App\Form\UserType;
use App\Message\SmsCreateUser;
use App\Message\SmsNotificateOrder;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/list/{email}', name: 'app_order_list', methods: ['GET'])]
    public function index(User $user, DocumentManager $documentManager): JsonResponse
    {
        $orders = $documentManager->getRepository()->findBy(['userEmail' => $user->getId()]);

        return $this->json($orders);
    }

    #[Route('{id}', name: 'app_order_find', methods: ['GET'])]
    public function find(Order $order): JsonResponse
    {
        return $this->json($order);
    }

    #[Route('/new', name: 'app_order_new', methods: ['POST'])]
    public function create(Request $request, DocumentManager $documentManager, MessageBusInterface $bus): JsonResponse
    {
        $tempUser = new User();
        $userForm = $this->createForm(UserType::class, $tempUser);
        $userForm->handleRequest($request);
        $tempUser = $userForm->getData();
        var_dump($tempUser);
        die();
        $user = $documentManager
            ->getRepository(User::class)
            ->findBy(["email" => $tempUser->getEmail()]);
        if (!$user){
            $bus->dispatch(new SmsCreateUser("Creating User", $tempUser));
        }

        $order = new Order();
        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $order = $form->getData();
            $documentManager->persist($order);
            $documentManager->flush();

            return $this->json($order);
        }

        $errors = $form->getErrors(true);
        $msg = [];
        foreach ($errors as $error) {
            $msg[] = $error->getMessage();
        }

        return $this->json($msg, Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}/edit/{email}', name: 'app_user_edit', methods: ['PUT'])]
    #[Entity('user', expr: 'repository.findBy([$email])')]
    public function edit(Order $order, User $user, Request $request, DocumentManager $documentManager): JsonResponse
    {
        $order->setUserEmail($user->getId());
        $form = $this->createForm(OrderType::class, $order);
        $form->submit($request->toArray());
        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->persist($order);
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
}
