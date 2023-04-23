<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Exception\Security\AccountNotVerifiedAuthenticationException;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

readonly class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    public function __construct(private RouterInterface $router)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10],
            LoginFailureEvent::class => 'onLoginFailure'
        ];
    }

    /**
     * @throws \Exception
     */
    public function onCheckPassport(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();
        $user = $passport->getUser();
        if (!$user instanceof User) {
            throw new Exception('Unexpected user type');
        }
        if (!$user->isVerified()) {
            throw new AccountNotVerifiedAuthenticationException();
        }
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        if (!$event->getException() instanceof AccountNotVerifiedAuthenticationException) {
            return;
        }
        $response = new RedirectResponse(
            $this->router->generate('register_verify_resend_email')
        );
        $event->setResponse($response);
    }
}