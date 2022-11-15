<?php

namespace App\Service\SignIn;

use App\DTO\SignIn\SignInDTO;
use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Form\SignInType;
use App\Service\Token\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;

class SignInService
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
        private TokenService $tokenService,
        private RouterInterface $router,
    ){}

    public function getSignInForm(): Form
    {
        return $this->containerInterface->get('form.factory')->create(SignInType::class, new SignInDTO());
    }

    /**
     * @throws UserNotFoundException
     */
    public function checkCreditionals(SignInDTO $signInDTO): User
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $signInDTO->getEmail(),
            'password' => $this->passwordHasher->hashPassword(new User(), $signInDTO->getPassword())
        ]);
        if (!$user){
            throw new UserNotFoundException();
        }
        return $user;
    }


    /**
     * @throws \Exception
     */
    public function setToken(User $user): RedirectResponse
    {
        if(null === $user->getApiToken()){
            $user->setApiToken(
                $this->generateToken()
            );
        }
        $this->entityManager->flush();
        $response = new RedirectResponse($this->router->generate('app_index'));
        $response->headers->setCookie(Cookie::create('token', $user->getApiToken()));
        return $response;
    }

    /**
     * @throws \Exception
     */
    private function generateToken(): string
    {
        return $this->tokenService->generateTokenString();
    }


}