<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Form\EquipmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Classes\Constants;
use App\Entity\Assign;
use App\Service\EquipmentService;
use App\Service\UserService;
use App\Repository\EquipmentRepository;
use App\Repository\AssignRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

#[Route('/equipment')]
class EquipmentController extends AbstractController
{
    private $em;
    private $equipmentRepository;
    private $userRepository;

    private $equipmentService;
    private $userService;
    private $categoryService;
    private $session;
    function __construct(
    EquipmentService $equipmentService
    , UserService $userService
    , RequestStack  $session
    )

    {
        $this->equipmentService = $equipmentService;
        $this->userService = $userService;
        $this->session = $session->getSession();
    }

    #[Route('/', name: 'app_equipment_index', methods: ['GET'])]
    public function index(): Response
    {
        // $equipments = $this->equipmentRepository->findAll();
        $equipments = $this->equipmentService->getAll();

        $users = $this->userService->getAll();
    
        return $this->render('equipment/index.html.twig', [
            'equipments' => $equipments,
            'users' => $users,
            'STATUS_IN_USE' =>  Constants::STATUS_IN_USE
        ]);
    }

    #[Route('/new', name: 'app_equipment_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {   
        $equipments = new Equipment();
        $form = $this->createForm(EquipmentType::class, $equipments);

        $check = $this->equipmentService->create($request, $form);

        if ($check) {
            return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('equipment/new.html.twig', [
            'equipments' => $equipments,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipment_show', methods: ['GET'])]
    public function show(Equipment $equipment): Response
    {
        $history = $this->equipmentService->getHistory($equipment);
        return $this->render('equipment/show.html.twig', [
            'equipments' => $equipment,
            'histories' => $history,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipment_edit', methods: ['GET', 'POST'])]
    public function edit($id,Request $request): Response
    {
        $equipment = $this->equipmentService->findOne($id);
        $form = $this->createForm(EquipmentType::class, $equipment);
        $check = $this->equipmentService->edit($request,$form);
        if ($check) 
        {
            return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('equipment/edit.html.twig', [
            'equipments' => $equipment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipment_delete', methods: ['POST'])]
    public function delete($id, Request $request): Response
    {
        $this->equipmentService->delete($id,$request);
        return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/assign', name: 'app_equipment_assign', methods: ['POST'])]
    public function assign($id, Request $request): Response
    {
        $this->equipmentService->assign($id,$request);
        return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/unassign', name: 'app_equipment_unassign', methods: ['POST'])]
    public function unassign($id, Request $request): Response
    {
        $this->equipmentService->unassign($id,$request);
        return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/search_user', name: 'app_equipment_search_user', methods: ['GET'])]
    public function search_user(Request $request): Response
    {
        $name = $request->query->get('value');
        if ($name) {
            $result = $this->userRepository->findByName($name);
            return $this->json($result);
        }
        return $this->json($name);
    }
}
