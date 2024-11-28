<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Request\User\UpdateUserRequest;
use App\Response\Response;
use App\Response\UserResponse;
use App\Service\UserService;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

final readonly class UpdateUserAction
{
    public function __construct(private UserService $userService)
    {
    }

    #[Route('/api/user', name: 'update_user', methods: ['PUT'])]
    public function __invoke(#[MapRequestPayload] UpdateUserRequest $updateUserRequest): Response
    {
        $user = $this->userService->getLoggedInUser();

        if (!$user) {
            throw new AccessDeniedHttpException();
        }

        $updatedUser = $this->userService->update($user, $updateUserRequest);

        return new Response(
            data: $updatedUser,
            responseClass: UserResponse::class
        );
    }
}
