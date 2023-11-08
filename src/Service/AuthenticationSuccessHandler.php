<?php
namespace App\Service;

use AllowDynamicProperties;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;


class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface {


    public function __construct(private UrlGeneratorInterface $router){

    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        $id = $token->getUser()->getId();
        var_dump($id);
        $url = $this->router->generate('user.show', ['id' => $id]);
        return new RedirectResponse($url);
    }
}