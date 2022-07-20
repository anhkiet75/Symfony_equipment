<?php

namespace App\Service;

use App\Classes\Constants;
use App\Entity\Equipment;
use App\Repository\EquipmentRepository;
use App\Repository\UserRepository;
use App\Repository\AssignRepository;

use App\Form\EquipmentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EquipmentService extends AbstractController
{
    private $assignRepository;
    private $equipmentRepository;
    private $userRepository;
    private $session;
    private $validator; 
    
    public function __construct(UserRepository $userRepository, AssignRepository $assignRepository,EquipmentRepository $equipmentRepository,RequestStack  $session,ValidatorInterface $validator) {
        $this->equipmentRepository = $equipmentRepository;
        $this->assignRepository = $assignRepository;
        $this->userRepository = $userRepository;
        $this->session = $session->getSession();
        $this->validator = $validator;
    }

    public function getAll() {
        return $this->equipmentRepository->getAll();
    }

    public function findOne($id) {
        return $this->equipmentRepository->findOne();
    }

    public function create(Request $request,$form) {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $equipment = $form->getData();
            $this->equipmentRepository->setStatus($equipment,constants::STATUS_AVAILABLE);
            
            $errors = $this->validator->validate($equipment);
            
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $this->session->set('failed',$errorsString);
            }
            else {
                $this->equipmentRepository->add($equipment,true);
                $this->session->set('success','Equipment created');
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
                $this->session->set('failed',$errorsString);
            }
            else {
                $this->equipmentRepository->update($equipment,true);
                $this->session->set('success','Equipment updated');
            }
                
            return true;
        }
        return false;
    }

    public function delete($id,Request $request) {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $this->equipmentRepository->delete($id,true);
            $this->session->set('success','Equipment deleted');
        }
        else $this->session->set('failed','Unable to delete');
    }

    public function assign($id, Request $request) {
        if ($this->isCsrfTokenValid('assign', $request->request->get('_token'))) {
            $equipment = $this->equipmentRepository->findOne($id);
            $user_id = $request->request->get('user_id');
            $user = $this->userRepository->findOne($user_id);

            $this->assignRepository->store($user,$equipment);
            $this->equipmentRepository->setStatus($equipment,Constants::STATUS_IN_USE);
            $this->session->set('success','Equipment assigned');
        }
        else $this->session->set('failed','Unable to assign');
    }

    public function unassign($id, Request $request) {
        if ($this->isCsrfTokenValid('unassign', $request->request->get('_token'))) {
            $equipment = $this->equipmentRepository->findOne($id);
            $this->equipmentRepository->setStatus($equipment,Constants::STATUS_AVAILABLE);
            $this->session->set('success','Equipment unassigned');
        }
        else $this->session->set('failed','Unable to unassign');
    }
}