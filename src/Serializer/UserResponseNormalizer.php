<?php
declare(strict_types=1);

namespace App\Serializer;

use App\Response\UserResponse;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class UserResponseNormalizer implements DenormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private Security            $security
    ) {
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): UserResponse
    {
        /** @var UserResponse $userResponse */
        $userResponse = $this->normalizer->denormalize($data, $type, $format, $context);

        $loggedInUser = $this->security->getUser();

        // Append the JWT to the user response if the currently authenticated user === the user requested
        if ($loggedInUser !== null && $loggedInUser->getUserIdentifier() === $userResponse->getUsername()) {
            $userResponse->setToken($this->security->getToken()->getCredentials());
        }

        return $userResponse;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === UserResponse::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            UserResponse::class => true
        ];
    }
}
