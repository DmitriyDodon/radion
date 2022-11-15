<?php

namespace App\Service\LogOut;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LogOutService
{
    public function __construct(
        private RouterInterface $router,
        private EntityManagerInterface $em,
    ){}

    public function removeToken(UserInterface $user): RedirectResponse
    {
        return match ($user::class){
            User::class => $this->userProvider($user)
        };
    }

    private function userProvider(User $user): RedirectResponse
    {
        $response = new RedirectResponse($this->router->generate('app_index'));
        $user->setApiToken(null);
        $response->headers->clearCookie('token');
        $this->em->flush();
        return $response;
    }
}