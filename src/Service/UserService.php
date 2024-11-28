<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Context\ContextBuilderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @extends DoctrineCrudService<User>
 */
class UserService extends DoctrineCrudService
{
    public function __construct(
        EntityManagerInterface $entityManager,
        NormalizerInterface&DenormalizerInterface $serializer,
        private readonly Security $security
    )
    {
        parent::__construct($entityManager, $serializer);
    }

    /**
     * @return User|null
     */
    public function getLoggedInUser(): ?User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }

    public function createContext(): ?ContextBuilderInterface
    {
        return null;
    }

    public function updateContext(): ?ContextBuilderInterface
    {
        return null;
    }

    public function getEntityClass(): string
    {
        return User::class;
    }
}
