<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
    #[Route('/contact/details/{id}', name: 'contact_details')]
    public function contact_details($id, ManagerRegistry $doctrine) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $contact = $doctrine->getRepository(Contact::class)->find($id);
        //dd($contact);
        return $this->render('contact/detail.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/contact/delete/{id}', name:'contact_delete')]
    public function  deleted(ManagerRegistry $doctrine, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $contact = $doctrine->getRepository(Contact::class)->find($id);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($contact);
        $entityManager->flush();

        return $this->redirectToRoute('app_contact');
    }

    #[Route('/contact/add', name:'contact_add')]
    public function annonces_add(ManagerRegistry $doctrine, Request $request)
    {
        $entityManager = $doctrine->getManager();
        
        $contact = new Contact();

        $formContact = $this->createForm(ContactFormType::class, $contact);

        $formContact->handleRequest($request);
        if($formContact->isSubmitted() && $formContact->isValid()){
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('sucess', "Le contact a bien ete ajoutee!");

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/contact-add.html.twig', [
            'formContact' => $formContact->createView()
        ]);
    }

    #[Route('/contact/edit/{id}', name:'contact_edit')]
    public function edit($id, ManagerRegistry $doctrine, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $entityManager = $doctrine->getManager();
        $contact = $doctrine->getRepository(Contact::class)->find($id);

        $formContact = $this->createForm(ContactFormType::class, $contact);

        $formContact->handleRequest($request);
        if($formContact->isSubmitted() && $formContact->isValid()){
            $entityManager->flush();

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/contact-edit.html.twig', [
            'formContact' => $formContact->createView()
        ]);
    }

}
