<?php

namespace App\Controller;

use App\Exception\User\UserNotFoundException;
use App\Service\SignIn\SignInService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignInController extends AbstractController
{
    /**
     * @throws UserNotFoundException
     * @throws \Exception
     */
    #[Route(path: '/signin', name: 'app_sign_in')]
    public function login(
        Request $request,
        SignInService $signInService
    ): Response
    {
        $form = $signInService->getSignInForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $signInService->checkCreditionals($form->getData());
            return $signInService->setToken($user);
        }

        return $this->render('security/login.html.twig',[
            'form' => $form->createView()
        ]);
    }
}