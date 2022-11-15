<?php

namespace App\Controller;

use App\Service\LogOut\LogOutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogOutController extends AbstractController
{
    #[Route(path: '/logout', name: 'app_logout')]
    public function logOut(
        LogOutService $logOutService,
    )
    {
        return $logOutService->removeToken($this->getUser());
    }
}