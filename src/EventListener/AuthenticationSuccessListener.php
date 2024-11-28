<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success')]
final readonly class AuthenticationSuccessListener
{
    /**
     * Authentication is handled by a bundle, the code below formats the response to a successful authentication
     */
    public function __invoke(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        // Ideally would like to use the Responder here...
        $response = [
            'email' => $user->getUserIdentifier(),
            'token' => $data['token'],
            'username' => $user->getUsername(),
            'bio' => $user->getBio(),
            'image' => $user->getImage(),
        ];

        $event->setData(['user' => $response]);
    }
}
