<?php

namespace App\Controller;

use App\Entity\Terrain;
use App\Entity\Partenaire;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Form\TerrainType;
use App\Repository\TerrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/terrain')]
class TerrainController extends AbstractController
{
    #[Route('/', name: 'app_terrain_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator,TerrainRepository $terrainRepository,Request $request): Response
    {
        $data=$terrainRepository->findAll();
        $terrains=$paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            3

        );
        return $this->render('terrain/index.html.twig', [
            'terrains' => $terrains,
        ]);
    }

    #[Route('/list', name: 'app_terrainFront_index', methods: ['GET'])]
    public function indexFront(PaginatorInterface $paginator,TerrainRepository $terrainRepository,Request $request): Response
    {
        $data=$terrainRepository->findAll();
        $terrains=$paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            3

        );
        return $this->render('terrain/indexFront.html.twig', [
            'terrains' => $terrains,
        ]);
    }

    #[Route('/new', name: 'app_terrain_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TerrainRepository $terrainRepository): Response
    {
        $terrain = new Terrain();
        
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $terrainRepository->save($terrain, true);

            return $this->redirectToRoute('app_terrain_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('terrain/new.html.twig', [
            'terrain' => $terrain,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_terrain_show', methods: ['GET'])]
    public function show(Terrain $terrain): Response
    {
        return $this->render('terrain/show.html.twig', [
            'terrain' => $terrain,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_terrain_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Terrain $terrain, TerrainRepository $terrainRepository): Response
    {
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $terrainRepository->save($terrain, true);

            return $this->redirectToRoute('app_terrain_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('terrain/edit.html.twig', [
            'terrain' => $terrain,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_terrain_delete', methods: ['POST'])]
    public function delete(Request $request, Terrain $terrain, TerrainRepository $terrainRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$terrain->getId(), $request->request->get('_token'))) {
            $terrainRepository->remove($terrain, true);
        }

        return $this->redirectToRoute('app_terrain_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/export/pdf', name: 'pdft', methods: ['GET'])]
    public function pdfd (TerrainRepository $terrainRepository,Request $request): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
//        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

//        $produit = $produitRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $data=$terrainRepository->findAll();
        $html = $this->renderView('terrain/pdf.html.twig',[
            'terrains' => $data,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Terrain.pdf", [
            "Attachment" => true
        ]);
        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    #[Route('/search/searcht', name: 'search_terrain', methods: ['GET'])]
    public function searchTerrain(Request $request, PaginatorInterface $paginator)
    {
        $terrains=$paginator->paginate(
            $this->getDoctrine()->getRepository(Terrain::class)->findBy(['adresse' => $request->get('search')]),
            $request->query->getInt('page',1),
            3

        );
        dump($request->get('search'));
        if (null != $request->get('search')) {
            return $this->render('/terrain/indexFront.html.twig', [
                'terrains' => $terrains
            ]);
        }
        return  $this->redirectToRoute('terrain_index');
    }
}
