<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Response\Response;
use App\Response\UserResponse;
use App\Service\UserService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

final readonly class GetUserAction
{
    public function __construct(private UserService $userService)
    {
    }

    #[Route('/api/user', name: 'get_user', methods: ['GET'])]
    public function __invoke(): Response
    {
        $user = $this->userService->getLoggedInUser();

        if (!$user) {
            throw new AccessDeniedHttpException();
        }

        return new Response(
            data: $user,
            responseClass: UserResponse::class
        );
    }
}
