<?php

namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Form\BienImmobilierType;
use App\Repository\BienImmobilierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bien/immobilier')]
class BienImmobilierController extends AbstractController
{
    #[Route('/', name: 'app_bien_immobilier_index', methods: ['GET'])]
    public function index(BienImmobilierRepository $bienImmobilierRepository): Response
    {
        return $this->render('bien_immobilier/index.html.twig', [
            'bien_immobiliers' => $bienImmobilierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bien_immobilier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bienImmobilier = new BienImmobilier();
        $form = $this->createForm(BienImmobilierType::class, $bienImmobilier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bienImmobilier);
            $entityManager->flush();

            return $this->redirectToRoute('app_bien_immobilier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bien_immobilier/new.html.twig', [
            'bien_immobilier' => $bienImmobilier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_immobilier_show', methods: ['GET'])]
    public function show(BienImmobilier $bienImmobilier): Response
    {
        return $this->render('bien_immobilier/show.html.twig', [
            'bien_immobilier' => $bienImmobilier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bien_immobilier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BienImmobilier $bienImmobilier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BienImmobilierType::class, $bienImmobilier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bien_immobilier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bien_immobilier/edit.html.twig', [
            'bien_immobilier' => $bienImmobilier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_immobilier_delete', methods: ['POST'])]
    public function delete(Request $request, BienImmobilier $bienImmobilier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bienImmobilier->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($bienImmobilier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bien_immobilier_index', [], Response::HTTP_SEE_OTHER);
    }
}
