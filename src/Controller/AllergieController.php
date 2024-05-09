<?php

namespace App\Controller;

use App\Entity\Allergie;
use App\Entity\InformationEducatif;
use App\Entity\Notification;
use App\Form\AllergieType;
use App\Repository\AllergieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/allergie")
 */
class AllergieController extends AbstractController
{
    #[Route('/allergie', name: 'app_allergie')]
    public function index(): Response
    {
        return $this->render('allergie/index.html.twig', [
            'controller_name' => 'AllergieController',
        ]);
    }

     
    #[Route('/Add', name:"Add_Allergie")]
     

        public function AddAllergie(ManagerRegistry $doctrine , Request $request,EntityManagerInterface $entityManager): Response
        {
            $allergie = new Allergie();
            $form = $this->createForm(AllergieType::class, $allergie);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($allergie);
                $entityManager->flush();
               
                $this->addFlash('success', 'Allergie ajouter avec succès.');
    
                
                // Redirection vers une autre page par exemple
                return $this->redirectToRoute('allergie_show');
            }
    
            return $this->render('allergie/new.html.twig', [
                'formA' => $form->createView(),
            ]);
        }
        
        #[Route('/Delete/{id}', name:"Allergie_delete")]
   
    public function DeleteAllergie($id, AllergieRepository $allergieRepository,ManagerRegistry $doctrine ): Response{
        
        {
            $em = $doctrine->getManager();
       
            $allergie =  $em ->getRepository(Allergie::class)->find($id);
    
            if (!$allergie) {
                throw $this->createNotFoundException('Aucune allergie trouvée pour cet id: '.$id);
            }
    
            // Vérifier si l'ID de l'allergie est utilisé comme clé étrangère dans une autre table
            $isUsedInOtherTable = $allergieRepository->checkIfUsedInOtherTable($id);

            if ($isUsedInOtherTable) {
                // Afficher un message d'erreur
                $this->addFlash('error', 'Impossible de supprimer l\'allergie car elle est utilisée dans une autre table.');
                return $this->redirectToRoute('route_to_redirect');
            }
    
            // Si l'allergie n'est pas utilisée dans une autre table, supprimer l'allergie
            $em ->remove($allergie);
            $em ->flush();
    
            $this->addFlash('success', 'Allergie supprimée avec succès.');
    
            return $this->redirectToRoute('allergie_show');
        }
    
    }
    
    #[Route('/show', name:"allergie_show")]
     
    public function show(AllergieRepository $allergieRepository):Response
    {
        $allergies = $allergieRepository->findAll();
        return $this->render('allergie/show.html.twig', ['allergies' => $allergies]);
    }


    #[Route('/affiche', name:"allergie_affiche")]
    public function affiche(AllergieRepository $allergieRepository):Response
    {
        $Allergies = $allergieRepository->findAll();
        return $this->render('allergie/feauter.html.twig',['allergies'=>$Allergies]);

        
    }
    #[Route('/update/{id}', name:"allergie_update")]
    
        public function updateAllergie(ManagerRegistry $doctrine, Request $request, AllergieRepository $rep, $id): Response
        {
            $allergie = $rep->find($id);
    $form = $this->createForm(AllergieType::class, $allergie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->persist($allergie);
        $em->flush();
        
        // Passer un message de succès à la vue
        $this->addFlash('success', 'Allergie modifiée avec succès !');

        return $this->redirectToRoute('allergie_show');
    }

    return $this->render('allergie/update.html.twig', [
        'formA' => $form->createView(),
    ]);}
     
    /**
     * @Route("/select", name="select_allergie")
     */
    public function select(Request $request, AllergieRepository $allergieRepository): Response
    {
        $id = $request->request->get('id');
       // $id_usr = $this->getUser()->getId(); // Vous devrez peut-être adapter cela selon la façon dont vous gérez l'authentification

        $allergieRepository->addUserAllergieAssociation( $id);

        // Vous pouvez retourner une réponse appropriée si nécessaire
        return $this->redirectToRoute('back/base.html.twig'); // Redirigez vers une autre page après la sélection, par exemple la page d'accueil
    }
    #[Route('/search_allergies', name:"search_allergies")]
    public function searchAllergies(Request $request,AllergieRepository $allergieRepository): Response
    {
        $query = $request->request->get('query');
        $filter = $request->request->get('filter'); // Ajoutez cette ligne pour récupérer le filtre

        $allergies = $allergieRepository->findBySearchQuery($query, $filter);

        return $this->render('allergie/search_results.html.twig', [
            'allergies' => $allergies,
        ]);
    }
  #[Route('/allergie/pdf/{id}', name:"allergie_pdf")]
     
    public function generatePdf(Allergie $allergie): Response
    {
        
        $pathToImage = 'C:\Users\rahma\OneDrive\Desktop\pidev\TounsifitWeb-rahma\public\logo.jpg';
        $imageData = base64_encode(file_get_contents($pathToImage));
        



        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        // Construisez le contenu HTML du PDF en utilisant une vue Twig
        $html = $this->renderView('allergie/pdf.html.twig', [
            'allergie' => $allergie,
            'imageData' => $imageData,

        ]);
        // Chargez le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);
        // Rendez le PDF
        $dompdf->render();
        // Renvoyez une réponse avec le contenu du PDF
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            array(
                'Content-Type' => 'application/pdf',)
        );
    }



}