<?php
declare(strict_types=1);

namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements DenormalizerInterface
{
    public const string ALREADY_CALLED = 'USER_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer
    ) {
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): User
    {
        /** @var User $user */
        $user = $this->normalizer->denormalize($data, $type, $format, $context);

        if ($user->getPassword() !== null) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);
        }

        return $user;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $type === User::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true
        ];
    }
}
