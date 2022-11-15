<?php declare(strict_types=1);

namespace App\Security;

use App\Controller\ApiController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {

        return $request->headers->has('token') || $request->cookies->get('token');
    }

    public function __construct(
        private EntityManagerInterface $em,
        private RouterInterface $router,
    ){}

    public function getCredentials(Request $request)
    {
        if ($request->headers->get('token')) {
            return $request->headers->get('token');
        }
        return $request->cookies->get('token');

    }

    public function authenticate(Request $request): PassportInterface
    {
        if ($request->headers->get('token')) {
            $apiToken = $request->headers->get('token');
        } else {
            $apiToken = $request->cookies->get('token');
        }
        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $passport = new SelfValidatingPassport(new UserBadge($apiToken, function ($apiToken) {
            $user = $this->em->getRepository(User::class)->findOneBy([
                'apiToken' => $apiToken
            ]);
            if (!$user) throw new CustomUserMessageAuthenticationException('Invalid token');
            return $user;
        }));

        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->router->generate('app_sign_in'));
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (null === $credentials) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            return null;
        }

        return $userProvider->loadUserByIdentifier((string)$credentials);
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse(
            [
                'error' => ApiController::API_ERROR_UNAUTHORIZED,
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => 'header with token not provided',
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}
