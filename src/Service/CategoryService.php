<?php

namespace App\Service;

use App\Classes\Constants;
use App\Entity\Category;
use App\Repository\CategoryRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Knp\Component\Pager\PaginatorInterface;

class CategoryService extends AbstractController
{
    private $categoryRepository;
    private $paginator;
    private $validator; 
    
    public function __construct(PaginatorInterface $paginator, CategoryRepository $categoryRepository,ValidatorInterface $validator) {
        $this->categoryRepository = $categoryRepository;
        $this->validator = $validator;
        $this->paginator = $paginator;

    }

    public function getAll() {
        return $this->categoryRepository->getAll();
    }

    public function getAllPaginate(Request $request) {
        $result = $this->categoryRepository->getAll();
        return $this->paginator->paginate(
            $result, $request->query->getInt('page', 1), 10 
        );
    }


    public function getList(Category $entity) {
        return $this->categoryRepository->getList($entity);
    }

    public function create(Request $request, $form) {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $errors = $this->validator->validate($category);
            
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $this->addFlash('failed',$errorsString);
            }
            else {
                $this->categoryRepository->add($category,true);
                $this->addFlash('success','Category created');
            }
            return true; 
        }
        return false;
    }

    public function edit(Request $request, $form) {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $errors = $this->validator->validate($category);
            
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $this->addFlash('failed',$errorsString);
            }
            else {
                $this->categoryRepository->update($category,true);
                $this->addFlash('success','Category updated');
            }
            return true;
        }
        return false;
    }

    public function delete(Request $request, Category $category) {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->categoryRepository->remove($category, true);
            $this->addFlash('success','Category deleted');
        }
        else $this->addFlash('failed','Unable to delete');
    }

    public function search($id) {
        return $this->categoryRepository->search($id);
    }

}