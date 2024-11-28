<?php
declare(strict_types=1);

namespace App\EventListener;

use JsonException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event: 'kernel.request')]
final class NestedJsonRequestListener
{
    private array $nestedKeys = [
        'user',
        'article',
        'comment'
    ];

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Only handle JSON requests
        if ($request->getContentTypeFormat() !== 'json') {
            return;
        }

        // Only handle requests going to controllers in App\Controller
        if (!str_contains($request->attributes->get('_controller'), 'App\Controller')) {
            return;
        }

        try {
            $payload = json_decode((string) $request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            // Symfony provides #[MapRequestPayload] attribute which will map the JSON to a DTO
            // However the requests come with the entity name as the key - e.g. {"user": {"name": "John Doe"}}
            // This code alters the request to remove the entity name - e.g. {"name": "John Doe"}
            // it's a bit of a hack....
            if (is_array($payload) && in_array(key($payload), $this->nestedKeys, true)) {
                $request->initialize(
                    $request->query->all(),
                    $request->request->all(),
                    $request->attributes->all(),
                    $request->cookies->all(),
                    $request->files->all(),
                    $request->server->all(),
                    json_encode(array_values($payload)[0])
                );
            }
        } catch (JsonException $e) {
            // @todo
        }
    }

}
