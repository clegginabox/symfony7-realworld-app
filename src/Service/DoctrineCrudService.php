<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Entity;
use App\Request\RequestDto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Serializer\Context\ContextBuilderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @template Entity of object
 */
abstract class DoctrineCrudService
{
    /**
     * @var EntityRepository<Entity>
     */
    protected readonly EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NormalizerInterface&DenormalizerInterface $serializer
    ) {
        $this->repository = $this->entityManager->getRepository($this->getEntityClass());
    }

    /**
     * Converts the RequestDto into an entity and persists it.
     */
    public function create(RequestDto $dto): object
    {
        $context = $this->createContext()?->toArray() ?? [];

        $entity = $this->serializer->denormalize(
            data: $this->serializer->normalize($dto),
            type: $this->getEntityClass(),
            context: $context
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * Merges the RequestDto into the existing entity and persists it.
     */
    public function update(Entity $entityToUpdate, RequestDto $dto): object
    {
        $context = $this->updateContext()?->toArray() ?? [AbstractNormalizer::OBJECT_TO_POPULATE => $entityToUpdate];

        $entity = $this->serializer->denormalize(
            data: $this->serializer->normalize($dto),
            type: $this->getEntityClass(),
            context: $context
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * Provides the entity to be handled
     *
     * @return class-string<Entity>
     */
    abstract public function getEntityClass(): string;

    /**
     * Provides context to be used by normalizer on create.
     */
    abstract public function createContext(): ?ContextBuilderInterface;


    /**
     * Provides context to be used by normalizer on update.
     */
    abstract public function updateContext(): ?ContextBuilderInterface;
}
