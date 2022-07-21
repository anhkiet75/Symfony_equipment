<?php

namespace App\Service;

use App\Classes\Constants;
use App\Entity\Equipment;
use App\Entity\User;
use App\Repository\EquipmentRepository;
use App\Repository\UserRepository;
use App\Repository\AssignRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserService extends AbstractController
{
    private $userRepository;
    private $session;
    private $validator; 
    
    public function __construct(UserRepository $userRepository, RequestStack  $session,ValidatorInterface $validator) {
        $this->userRepository = $userRepository;
        $this->session = $session->getSession();
        $this->validator = $validator;
    }

    public function getAll() {
        return $this->userRepository->getAll();
    }

    public function getListEquipment(User $entity) {
        return $this->userRepository->getListEquipment($entity);
    }

}