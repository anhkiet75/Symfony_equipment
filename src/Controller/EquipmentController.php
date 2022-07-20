<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Form\EquipmentType;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Classes\Constants;
use App\Entity\Assign;
use App\Repository\AssignRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/equipment')]
class EquipmentController extends AbstractController
{
    private $em;
    private $equipmentRepository;
    private $userRepository;
    private $assignRepository;
    function __construct(EntityManagerInterface $em, EquipmentRepository $equipmentRepository, UserRepository $userRepository, AssignRepository $assignRepository)
    {
        $this->em = $em;
        $this->equipmentRepository = $equipmentRepository;
        $this->userRepository = $userRepository;
        $this->assignRepository = $assignRepository;
    }

    #[Route('/', name: 'app_equipment_index', methods: ['GET'])]
    public function index(): Response
    {
        $equipments = $this->equipmentRepository->findAll();
        $users = $this->userRepository->findAll();
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
        $equipments->setStatus(Constants::STATUS_AVAILABLE);
        $form = $this->createForm(EquipmentType::class, $equipments);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($equipments);
            $this->em->flush();

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
        return $this->render('equipment/show.html.twig', [
            'equipments' => $equipment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipment_edit', methods: ['GET', 'POST'])]
    public function edit($id,Request $request): Response
    {
        $equipment = $this->equipmentRepository->find($id);

        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

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
        $equipment = $this->equipmentRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$equipment->getId(), $request->request->get('_token'))) {
            $this->em->remove($equipment);
            $this->em->flush(); 
        }

        return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/assign', name: 'app_equipment_assign', methods: ['POST'])]
    public function assign($id, Request $request): Response
    {
        $equipment = $this->equipmentRepository->find($id);
        $user_id = $request->request->get('user_id');
        $user = $this->userRepository->find($user_id);
        if ($this->isCsrfTokenValid('assign', $request->request->get('_token'))) {
            $assign = new Assign();
            $assign->setUser($user);
            $assign->setEquipment($equipment);

            $date_assign = new \DateTimeImmutable();
            $date_assign->format('Y-m-d H:i:s');
            $due_date = $date_assign->modify('+1 year');
            $assign->setDateAssign($date_assign);
            $assign->setDueDate($due_date);
            $this->em->persist($assign);
            //
            $equipment->setStatus(Constants::STATUS_IN_USE);
            $this->em->flush();
        }
        return $this->redirectToRoute('app_equipment_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/unassign', name: 'app_equipment_unassign', methods: ['POST'])]
    public function unassign($id, Request $request): Response
    {
        $equipment = $this->equipmentRepository->find($id);
        if ($this->isCsrfTokenValid('unassign'.$equipment->getId(), $request->request->get('_token'))) {
            // $user = $equipment->getLastUser();
            $equipment->setStatus(Constants::STATUS_AVAILABLE);
            $this->em->flush();
        }
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
