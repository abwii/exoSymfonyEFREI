<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Annonces;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AnnoncesType;

class AnnoncesController extends AbstractController
{
    #[Route('/annonces', name: 'app_annonces')]
    public function index(): Response
    {
        return $this->render('annonces/index.html.twig', [
            'controller_name' => 'AnnoncesController',
        ]);
    }

    #[Route('/annonces/add', name:'annonces_add')]
    public function annonces_add(ManagerRegistry $doctrine, Request $request)
    {
        $entityManager = $doctrine->getManager();

        $annonce = new Annonces();
        $annonce->setCreatedAt(new \DateTimeImmutable());

        $formAnnonce = $this->createForm(AnnoncesType::class, $annonce);

        $formAnnonce->handleRequest($request);
        if($formAnnonce->isSubmitted() && $formAnnonce->isValid()){
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('annonces/form-add.html.twig', [
            'formAnnonce' => $formAnnonce->createView()
        ]);
    }

    #[Route('/annonces/show/{id}', name:'annonces_show')]
    public function show(ManagerRegistry $doctrine, $id)
    {
        $annonce = $doctrine->getRepository(Annonces::class)->find($id);

        return $this->render('annonces/show.html.twig', [
           "annonce" => $annonce 
        ]);
    }

    #[Route('/annonces/edit/{id}', name:'annonces_edit')]
    public function edit(ManagerRegistry $doctrine, Request $request, $id)
    {
        $entityManager = $doctrine->getManager();
        $annonce = $doctrine->getRepository(Annonces::class)->find($id);

        $formAnnonce = $this->createForm(AnnoncesType::class, $annonce);

        $formAnnonce->handleRequest($request);
        if($formAnnonce->isSubmitted() && $formAnnonce->isValid()){
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('annonces/form-edit.html.twig', [
            'formAnnonce' => $formAnnonce->createView()
        ]);
    }

    #[Route('/annonces/delete/{id}', name:'annonces_delete')]
    public function  deleted(ManagerRegistry $doctrine, $id)
    {
        $annonce = $doctrine->getRepository(Annonces::class)->find($id);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}