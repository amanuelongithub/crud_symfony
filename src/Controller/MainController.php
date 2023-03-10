<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'main')]


    public function index(): Response
    {
        $data = $this->getDoctrine()->getRepository(Todo::class)->findAll();
        return $this->render('main/index.html.twig', [
            'list' => $data,
        ]);
    }

    #[Route('/create', name: 'create')]

    public function create(Request $request){
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) { 
           $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            $this->addFlash('notice','the data is successfuly submited!!'); 

            return $this->redirectToRoute('main');
        }        
        return $this->render('main/create.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'update')]

    public function update(Request $request, $id){

        $todo = $this->getDoctrine()->getRepository(Todo::class)->find($id);
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) { 
           $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            $this->addFlash('notice','Updated Successfluy!!'); 

            return $this->redirectToRoute('main');
        }        
        return $this->render('main/update.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]

    public function delete($id){
        $data = $this->getDoctrine()->getRepository(Todo::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice','Deleted Successfluy!!'); 

        return $this->redirectToRoute('main');
    }
}
