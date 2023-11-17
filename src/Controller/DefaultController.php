<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_contact')]
    public function index(ManagerRegistry $doctrine)
    {
        $contact = $doctrine->getRepository(Contact::class)->findAll();

        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
        ]);
    }
}