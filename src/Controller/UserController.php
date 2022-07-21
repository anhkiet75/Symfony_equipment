<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{

    private UserService $userService;

    function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userService->getAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $check = $this->userService->create($request, $form);
        if ($check) {
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        if ($user !== $this->getUser()) {
            return $this->redirectToRoute('app_user_show', ['id' => $this->getUser()->getId()]);
        }
        $equipments = $this->userService->getListEquipment($user);

        return $this->render('user/show.html.twig', [
            'equipments' => $equipments,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $check  = $this->userService->edit($request, $form);
        if ($check) {
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        $this->userService->delete($request, $user);
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

}
