<?php

namespace App\Service;

use App\Classes\Constants;
use App\Entity\Equipment;
use App\Repository\EquipmentRepository;
use App\Repository\UserRepository;
use App\Repository\AssignRepository;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EquipmentService extends AbstractController
{
    private $userRepository;
    private $assignRepository;
    private $equipmentRepository;
    private $session;
    private $validator; 
    
    public function __construct(UserRepository $userRepository, AssignRepository $assignRepository,EquipmentRepository $equipmentRepository,RequestStack  $session,ValidatorInterface $validator) {

        $this->userRepository = $userRepository;
        $this->assignRepository = $assignRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->session = $session->getSession();
        $this->validator = $validator;
    }

    public function getAll() {
        return $this->equipmentRepository->getAll();
    }

    public function findOne($id) {
        return $this->equipmentRepository->findOne($id);
    }

    public function getHistory(Equipment $entity) {
        return $this->equipmentRepository->getHistory($entity);
    }

    public function create(Request $request,$form) {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $equipment = $form->getData();
            $this->equipmentRepository->setStatus($equipment,constants::STATUS_AVAILABLE);
            
            $errors = $this->validator->validate($equipment);
            
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $this->addFlash('failed',$errorsString);
            }
            else {
                $this->equipmentRepository->add($equipment,true);
                $this->addFlash('success','Equipment created');
            }
            return true;
        }
        return false;
    }

    public function edit(Request $request,$form) { 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $equipment = $form->getData();
            $errors = $this->validator->validate($equipment);
            
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $this->addFlash('failed',$errorsString);
            }
            else {
                $this->equipmentRepository->update($equipment,true);
                $this->addFlash('success','Equipment created');
            }
                
            return true;
        }
        return false;
    }

    public function delete($id,Request $request) {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $this->equipmentRepository->delete($id,true);
            $this->addFlash('success','Equipment deleted');
        }
        else $this->addFlash('failed','Unable to delete');
    }

    public function assign($id, Request $request) {
        if ($this->isCsrfTokenValid('assign', $request->request->get('_token'))) {
            $equipment = $this->equipmentRepository->findOne($id);
            $user_id = $request->request->get('user_id');
            $user = $this->userRepository->findOne($user_id);

            $this->assignRepository->store($user,$equipment);
            $this->equipmentRepository->setStatus($equipment,Constants::STATUS_IN_USE);
            $this->addFlash('success','Equipment assigned');
        }
        else $this->addFlash('failed','Unable to assign');
    }

    public function unassign($id, Request $request) {
        if ($this->isCsrfTokenValid('unassign', $request->request->get('_token'))) {
            $equipment = $this->equipmentRepository->findOne($id);
            $this->equipmentRepository->setStatus($equipment,Constants::STATUS_AVAILABLE);
            $this->addFlash('success','Equipment unassigned');        
        }
        else $this->addFlash('failed','Unable to unassign');
    }
}