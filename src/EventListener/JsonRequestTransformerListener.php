<?php

/**
 * This file is part of the qandidate/symfony-json-request-transformer package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\EventListener;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Transforms the body of a json request to POST parameters.
 */
class JsonRequestTransformerListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isJsonRequest($request)) {
            return;
        }

        $content = $request->getContent();

        if (empty($content)) {
            return;
        }

        if ($this->transformJsonBody($request)) {
            return;
        }

        $message = 'Unable to parse request.';
        $status = Response::HTTP_BAD_REQUEST;

        if ($this->isJsonResponse($request)) {
            $response = new JsonResponse(['exception' => ['message' => $message, 'code' => $status,],], $status);
        } else {
            $response = new Response($message, $status);
        }

        $event->setResponse($response);
    }

    private function isJsonRequest(Request $request): bool
    {
        return 'json' === $request->getContentType();
    }

    private function isJsonResponse(Request $request): bool
    {
        return in_array('application/json', $request->getAcceptableContentTypes(), true);
    }

    private function transformJsonBody(Request $request): bool
    {
        try {
            /** @var array<string, string|mixed> $data */
            $data = json_decode((string)$request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception) {
            return false;
        }

        if (null !== $data) {
            $request->request->replace($data);
        }

        return true;
    }
}
