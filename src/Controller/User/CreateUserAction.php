<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Request\User\CreateUserRequest;
use App\Response\Response;
use App\Response\UserResponse;
use App\Service\UserService;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final readonly class CreateUserAction
{
    public function __construct(private UserService $userService)
    {
    }

    #[Route('/api/users', name: 'create_user', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateUserRequest $createUserRequest): Response
    {
        $user = $this->userService->create($createUserRequest);

        return new Response(
            data: $user,
            responseClass: UserResponse::class
        );
    }
}
