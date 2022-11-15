<?php

namespace App\Controller;

use App\Security\ApiKeyAuthenticator;
use App\Service\SignIn\SignInService;
use App\Service\SignUp\SignUpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class SignUpController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route(path: '/signup', name: 'app_sign_up', methods: ["GET", "POST"])]
    public function signUp(
        SignUpService $signUpService,
        Request $request,
        SignInService $signInService,
    ): Response {
        $form = $signUpService->getSignUpForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $signUpService->createUser($form->getData());
            return $signInService->setToken($user);
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }
}