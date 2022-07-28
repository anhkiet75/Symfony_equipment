<?php

namespace App\Controller;

use App\Classes\Constants;
use App\Entity\User;
use App\Form\UserType;
use App\Service\CategoryService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/user')]
class UserController extends AbstractController
{

    private  $userService;
    private  $categoryService;

    function __construct(UserService $userService, CategoryService $categoryService ) {
        $this->userService = $userService;
        $this->categoryService = $categoryService;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $users = $this->userService->getAllPaginate($request);
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
        $categoies = $this->categoryService->getAll();
        return $this->render('user/show.html.twig', [
            'equipments' => $equipments,
            'categories' => $categoies,
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

    #[Route('/api/search', name: 'app_user_search', methods: ['GET'])]
    public function api_search(Request $request)
    {
        $value = $request->query->get('value');
        if ($value) {
            $result = $this->userService->search($value);
            $users = $this->userService->getAll();
            return $this->render('user/search.html.twig',[
                'equipments' => $result,
                'users' => $users,
                'STATUS_IN_USE' =>  Constants::STATUS_IN_USE
            ]);
        }
        return $this->json(["failed" => "Not accepted"]);
    }

    #[Route('/api/filter', name: 'app_user_filter', methods: ['GET'])]
    public function api_filter(Request $request)
    {
        $id = $request->query->get('id');
        if ($id) {
            $result = $this->userService->filterByCategory($id);
            // dd($result);
            $users = $this->userService->getAll();
            return $this->render('user/search.html.twig',[
                'equipments' => $result,
                'users' => $users,
                'STATUS_IN_USE' =>  Constants::STATUS_IN_USE
            ]);
        }
        return $this->json(["failed" => "Not accepted"]);
    }
}
