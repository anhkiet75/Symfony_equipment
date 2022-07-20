<?php

namespace App\Service;

use App\Classes\Constants;
use App\Entity\Equipment;
use App\Repository\EquipmentRepository;

use App\Form\EquipmentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;


class EquipmentService {

    private $equipmentRepository;
    private $session;
    private $validator; 
    
    public function __construct(EquipmentRepository $equipmentRepository,RequestStack  $session,ValidatorInterface $validator) {
        $this->equipmentRepository = $equipmentRepository;
        $this->session = $session->getSession();
        $this->validator = $validator;
    }

    public function getAll() {
        return $this->equipmentRepository->getAll();
    }

    public function create(Request $request,$form) {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $equipment = $form->getData();
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

    public function delete($id) {
        $this->equipmentRepository->delete($id,true);
        $this->session->set('success','Equipment deleted');
    }
}