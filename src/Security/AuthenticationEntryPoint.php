<?php
// src/Security/AuthenticationEntryPoint.php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
// use Symfony\Component\Security\Core\Security;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        // $this->security = $security;
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {   
        // add a custom flash message and redirect to the login page
        $request->getSession()->getFlashBag()->add('note', 'You have to login in order to access this page.');
        // $user = $this->security->getUser();
        // return new RedirectResponse($this->urlGenerator->generate('app_user_show',['id' => 62]));
        return new RedirectResponse($this->urlGenerator->generate('app_login'));

    }
}