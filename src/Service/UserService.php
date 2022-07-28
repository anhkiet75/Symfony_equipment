<?php

namespace App\Service;

use App\Classes\Constants;
use App\Entity\User;
use App\Repository\EquipmentRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;


class UserService extends AbstractController
{
    private $userRepository;
    private $session;
    private $equipmentRepository;
    private $validator; 
    private $paginator;
    
    public function __construct(PaginatorInterface $paginator, UserRepository $userRepository,EquipmentRepository $equipmentRepository, RequestStack  $session,ValidatorInterface $validator) {
        $this->userRepository = $userRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->session = $session->getSession();
        $this->validator = $validator;
        $this->paginator = $paginator;
    }

    public function getAll() {
        return $this->userRepository->getAll();
    }

    public function getAllPaginate($request) {
        $result = $this->userRepository->getAll();
        return $this->paginator->paginate(
            $result, $request->query->getInt('page', 1), 10 
        );
    }

    public function search($id) {
        return $this->userRepository->search($id);
    }


    public function filterByCategory($id) {
        return $this->userRepository->filterByCategory($id);
    }

    public function getListEquipment(User $entity) {
        return $this->userRepository->getListEquipment($entity);
    }

    public function create(Request $request, $form) {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $errors = $this->validator->validate($user);
            
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $this->addFlash('failed',$errorsString);
            }
            else {
                $this->userRepository->add($user,true);
                $this->addFlash('success','User created');
            }
            return true; 
        }
        return false;
    }

    public function edit(Request $request, $form) {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $errors = $this->validator->validate($user);
            
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $this->addFlash('failed',$errorsString);
            }
            else {
                $this->userRepository->update($user,true);
                $this->addFlash('success','User updated');
            }
            return true;
        }
        return false;
    }

    public function delete(Request $request, User $user) {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userRepository->remove($user, true);
            $this->addFlash('success','User deleted');
        }
        else $this->addFlash('failed','Unable to delete');
    }

    

}