<?php

namespace App\Controller;

use App\Document\Order;
use App\Document\User;
use App\Form\OrderType;
use App\Message\SmsCreateUser;
use App\Message\SmsNotificateOrder;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/list/{email}', name: 'app_order_list', methods: ['GET'])]
    public function index(User $user = null, DocumentManager $documentManager): JsonResponse
    {
        if (!$user) {
            return $this->json(null, 404);
        }

        $orders = $documentManager->getRepository()->findBy(['userId' => $user->getId()]);

        return $this->json($orders);
    }

    #[Route('{id}', name: 'app_order_find', methods: ['GET'])]
    public function find(Order $order = null): JsonResponse
    {
        if (!$order) {
            return $this->json(null, 404);
        }

        return $this->json($order);
    }

    #[Route('/new/{email}', name: 'app_order_new', methods: ['GET'])]
    public function create(User $user = null, Request $request, DocumentManager $documentManager, MessageBusInterface $bus): JsonResponse
    {
        $order = new Order();
        $order->setUserId($user->getId());
        $form = $this->createForm(OrderType::class, $order);
        $form->submit($request->toArray());
        if (!$user && $form->isValid()) {
            $data = $form->getData();
            $userData = [$data["name"], $data["surname"], $data["email"]];
            $bus->dispatch(new SmsCreateUser('Registering new user...', $userData));
            if ($form->isSubmitted()) {
                $documentManager->persist($order);
                $documentManager->flush();
                $bus->dispatch(new SmsNotificateOrder('Congratulations for your first order!!', $user->getId()));

                return $this->json($user, 200);
            }
        }

        $errors = $form->getErrors(true);
        $msg = [];
        foreach ($errors as $error) {
            $msg[] = $error->getMessage();
        }

        return $this->json($msg, 400);
    }

    #[Route('/{id}/edit/{email}', name: 'app_user_edit', methods: ['PUT'])]
    #[Entity('user', expr: 'repository.findBy([$email])')]
    public function edit(Order $order = null, User $user = null, Request $request, DocumentManager $documentManager): JsonResponse
    {
        if (!$user || !$order) {
            return $this->json(null, 404);
        }

        $order->setUserId($user->getId());
        $form = $this->createForm(OrderType::class, $order);
        $form->submit($request->toArray());
        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->persist($order);
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
}
