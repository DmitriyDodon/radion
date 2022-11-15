<?php

namespace App\Service\SignUp;

use App\DTO\SignUp\SignUpDTO;
use App\Entity\User;
use App\Form\SignUpType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SignUpService
{
    public function __construct(
        private ContainerInterface $containerInterface,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
    ){}

    public function getSignUpForm(): Form
    {
        return $this->containerInterface->get('form.factory')->create(SignUpType::class, new SignUpDTO());
    }

    public function createUser(SignUpDTO $signUpDTO): User
    {
        $user = new User();
        $user->setEmail($signUpDTO->getEmail());
        $password = $this->passwordHasher->hashPassword($user, $signUpDTO->getPassword());
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}