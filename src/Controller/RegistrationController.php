<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private $em;
    private $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->em->persist($user);

            $role = $this->em->getRepository(Role::class)->findOneBy([
                'name' => 'ROLE_USER'
            ]);
            $user->addRole($role);
            $this->em->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
