<?php
// src/Security/AccessDeniedHandler.php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;


class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $urlGenerator;
    private $user;
    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        $request->getSession()->getFlashBag()->add('note', "You don't have permission in order to access this page.");
        return new RedirectResponse($this->urlGenerator->generate('app_user_show',['id' => $this->security->getUser()->getId()], UrlGeneratorInterface::ABSOLUTE_URL));
    }
}