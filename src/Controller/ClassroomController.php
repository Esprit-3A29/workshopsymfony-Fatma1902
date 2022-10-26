<?php

namespace App\Controller;
use App\Entity\Classroom;
use App\Form\FormClassType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    #[Route('/classrooms', name: 'list_classroom')]
    public function listClassroom(ClassroomRepository $repository)
    {
        $classrooms = $repository->findAll();
        return $this->render("classrooms/listClassroom.html.twig",array("tabClassrooms"=>$classrooms));
    }

    #[Route('/addclassroom', name: 'add_classroom')]
    public function addClass(ManagerRegistry $doctrine,Request $request)
    {
        $classroom= new Classroom;
        $form= $this->createForm(FormClassType::class,$classroom);
        $form->handleRequest($request) ;
        if ($form->isSubmitted()){
             $em= $doctrine->getManager();
             $em->persist($classroom);
             $em->flush();
             return  $this->redirectToRoute("list_classroom");
         }
        return $this->renderForm("classroom/AddClass.html.twig",array("formclassroom"=>$form));
}
#[Route('/update/{id}', name: 'update')]
public function  updateForm($id,ClassroomRepository $repository,ManagerRegistry $doctrine,Request $request)
{
    $classroom = $repository->find($id);
    $form= $this->createForm(FromClassType::class,$classroom);
    $form->handleRequest($request) ;
    if ($form->isSubmitted()){
        $em= $doctrine->getManager();
        $em->flush();
        return  $this->redirectToRoute("list_classroom");
    }
    return $this->renderForm("classroom/update.html.twig",array("formClassroom"=>$form));
}

#[Route('/remove/{id}', name: 'remove')]

public function removeClassroom(ManagerRegistry $doctrine,$id,StudentRepository $repository)
{
    $classroom= $repository->find($id);
    $em = $doctrine->getManager();
    $em->remove($classroom);
    $em->flush();
    return  $this->redirectToRoute("list_classroom");
}
#[Route('/showClassroom/{id}', name: 'showClassroom')]
public function showClassroom(StudentRepository $repo,$id,ClassroomRepository $repository)
{
    $classroom = $repository->find($id);
   $students= $repo->getStudentsByClassroom($id);
    return $this->render("classroom/showclassroom.html.twig",array(
        'showClassroom'=>$classroom,
        'tabStudent'=>$students
    ));
}
}