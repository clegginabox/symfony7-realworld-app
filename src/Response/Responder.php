<?php
declare(strict_types=1);

namespace App\Response;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Serializer\SerializerInterface;

readonly class Responder
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function respond(Response $response, string $acceptHeader): SymfonyResponse
    {
        // Realworld app only needs to handle JSON but seeing as symfony's serializer supports
        // xml and csv as standard - might as well have a very basic implementation of content negotiation
        $format = match ($acceptHeader) {
            'application/xml' => 'xml',
            'text/csv' => 'csv',
            default => 'json',
        };

        $responseData = $response->getData();

        if (is_array($responseData)) {
            // Handles an array of objects
            $responseKey = (new \ReflectionClass($responseData[0]))->getShortName() . 's';

            $content = $this->serializer->denormalize(
                data: $this->serializer->normalize($responseData),
                type: $response->getResponseClass() . '[]',
            );
        } else {
            // Handles a single object
            $responseKey = (new \ReflectionClass($responseData))->getShortName();

            $content = $this->serializer->denormalize(
                data: $this->serializer->normalize($responseData),
                type: $response->getResponseClass(),
            );
        }

        return new SymfonyResponse(
            $this->serializer->serialize(
                [strtolower($responseKey) => $content],
                $format
            ),
            $response->getResponseCode()
        );
    }
}
