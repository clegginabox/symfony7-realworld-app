<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Response\Responder;
use App\Response\Response;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ViewEvent;

#[AsEventListener(event: 'kernel.view')]
final readonly class ResponseListener
{
    public function __construct(private Responder $responder)
    {
    }

    public function __invoke(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();

        if (!$controllerResult instanceof Response) {
            $event->setResponse($controllerResult);

            return;
        }

        $request = $event->getRequest();
        $acceptHeader = $request->headers->get('Accept', 'application/json');

        $event->setResponse(
            $this->responder->respond($controllerResult, $acceptHeader)
        );
    }
}
