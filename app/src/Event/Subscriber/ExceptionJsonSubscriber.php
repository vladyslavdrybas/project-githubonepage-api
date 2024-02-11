<?php

declare(strict_types=1);

namespace App\Event\Subscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use function var_dump;

class ExceptionJsonSubscriber implements EventSubscriberInterface
{
    protected string $environment;

    public function __construct(string $projectEnvironment)
    {
        $this->environment = $projectEnvironment;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // the priority must be greater than the Security HTTP
            // ExceptionListener, to make sure it's called before
            // the default exception listener
            KernelEvents::EXCEPTION => ['onKernelException', 100],
            Events::JWT_EXPIRED => 'jwtExpired',
        ];
    }

    public function jwtExpired(JWTExpiredEvent $event): void
    {
//        var_dump([
//            'apismanager',
//            $event->getRequest()->getPathInfo(),
//            'jwt expired'
//        ]);
//        $event->setResponse(new Response('KO',Response::HTTP_BAD_REQUEST));
//        $event->stopPropagation();
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        var_dump([
            'apismanager',
            $event->getRequest()->getPathInfo(),
        ]);

        if ($this->environment === 'prod') {
            $event->setResponse(new Response('KO',Response::HTTP_BAD_REQUEST));

            return;
        }

        $exception = $event->getThrowable();
        $code = Response::HTTP_BAD_REQUEST;
        $message = $exception->getMessage();
        if ($exception instanceof AccessDeniedException) {
            $code = Response::HTTP_UNAUTHORIZED;
            $message = 'Access denied';
        } else if ($exception instanceof NotFoundHttpException) {
            $code = Response::HTTP_NOT_FOUND;
            $message = '404 not found';
        } else if ($exception instanceof MethodNotAllowedException) {
            $code = Response::HTTP_METHOD_NOT_ALLOWED;
            $message = 'Method not allowed';
        }

        $data = [
            'message' => $message,
            'status' => $code,
            'environment' => $this->environment,
        ];

        $data['trace'] = $exception->getTrace();

        $event->setResponse(new JsonResponse($data,$code));
        // or stop propagation (prevents the next exception listeners from being called)
        //$event->stopPropagation();
    }
}
