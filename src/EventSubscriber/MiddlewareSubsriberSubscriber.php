<?php

namespace App\EventSubscriber;

use App\Controller\TokenAuthenticatedController;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;

class MiddlewareSubsriberSubscriber implements EventSubscriberInterface
{

    private $jwtManager;
    public function __construct(JWTEncoderInterface $encoder, EventDispatcherInterface $dispatcher)
    {
        $this->jwtManager = new JWTManager($encoder, $dispatcher);
    }


    public function onKernelController(ControllerEvent $event): void
    {
        // ...

        $controller = $event->getController();

        if (is_array($controller)) {
            $action = $controller[1];
            $controller = $controller[0];
        }

        if ($controller instanceof TokenAuthenticatedController && str_contains($action, 'api')) {

            $jwtToken = $event->getRequest()->cookies->get('jwt');

            if ($jwtToken) {
                try {
                    $roles = $this->jwtManager->parse($jwtToken)['roles'];
                    if (!in_array('ROLE_ADMIN', $roles)) {
                        $event->setController(function () {
                            return new JsonResponse([
                                "message" => "You are not authrorized",
                                "success" => false
                            ]);
                        });
                    }
                } catch (Exception $e) {
                    $event->setController(function () use ($e) {
                        return new JsonResponse([
                            "message" => $e->getMessage(),
                            "success" => false,                            
                        ]);
                    });
                }
            } else {
                $event->setController(function () {
                    return new JsonResponse([
                        "message" => "JWT token is required, please login again",
                        "success" => false
                    ]);
                });
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
