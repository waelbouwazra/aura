<?php

namespace App\Controller;

use App\Entity\Solde;
use App\Form\SoldeType;
use App\Repository\SoldeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/solde')]
class SoldeController extends AbstractController
{
    #[Route('/', name: 'app_part_index', methods: ['GET'])]
    public function index(SoldeRepository $soldeRepository): Response
    {
        return $this->render('part/index.html.twig', [
            'parts' => $soldeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_part_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SoldeRepository $soldeRepository): Response
    {
        $solde = new Solde();
        $form = $this->createForm(SoldeType::class, $solde);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soldeRepository->save($solde, true);

            return $this->redirectToRoute('app_part_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('part/new.html.twig', [
            'part' => $solde,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_part_show', methods: ['GET'])]
    public function show(Solde $solde): Response
    {
        return $this->render('part/show.html.twig', [
            'part' => $solde,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_part_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Solde $solde, SoldeRepository $soldeRepository): Response
    {
        $form = $this->createForm(SoldeType::class, $solde);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soldeRepository->save($solde, true);

            return $this->redirectToRoute('app_part_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('part/edit.html.twig', [
            'part' => $solde,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_part_delete', methods: ['POST'])]
    public function delete(Request $request, Solde $solde, SoldeRepository $soldeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solde->getId(), $request->request->get('_token'))) {
            $soldeRepository->remove($solde, true);
        }

        return $this->redirectToRoute('app_part_index', [], Response::HTTP_SEE_OTHER);
    }
}
