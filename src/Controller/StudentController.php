<?php

namespace App\Controller;
use App\Entity\Student;
use App\Form\FormstudentType;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/students', name: 'list_students')]
    public function liststudents(StudentRepository $repository)
    {
        $students = $repository->findAll();
        return $this->render("student/listStudent.html.twig",array("tabStudents"=>$students));
    }
    #[Route('/addstudent', name: 'add')]
    public function addStudent(StudentRepository $repository,ManagerRegistry $doctrine,Request $request)
    {
        $student= new Student;
        $form= $this->createForm(formstudentType::class,$student);
        $form->handleRequest($request) ;
        if ($form->isSubmitted()){
             $repository->add(student,true);
             return  $this->redirectToRoute("list_student");
         }
        return $this->renderForm("student/add.html.twig",array("formStudent"=>$form));
    }
}
