<?php

namespace App\Controller;
use TCPDF as TCPDF;
use App\Entity\InformationEducatif;
use App\Form\InformationType;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Repository\InformationEducatifRepository;
use App\Service\EmailSender;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;





/**
 * @Route("/information")
 */
class InfotmationEducatifController extends AbstractController
{
    #[Route('/infotmation/educatif', name: 'app_infotmation_educatif')]
    public function index(): Response
    {
        return $this->render('infotmation_educatif/index.html.twig', [
            'controller_name' => 'InfotmationEducatifController',
        ]);
    } #[Route('/Add', name:"Information_Add")]
     
    public function AddInformationEducatif(ManagerRegistry $doctrine, Request $request): Response
    {
        $information = new InformationEducatif();
        $form = $this->createForm(InformationType::class, $information);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier de l'image
            $imageFile = $form->get('image')->getData();
    
            // Vérifier si une image a été téléchargée
            if ($imageFile) {
                // Générer un nom unique pour l'image
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                // Déplacer le fichier vers le répertoire où les images doivent être sauvegardées
                try {
                    $imageFile->move($this->getParameter('images_directory'), $newFilename);
                } catch (FileException $e) {
                    // Gérer l'exception
                }
    
                // Mettre à jour le chemin de l'image dans l'entité
                $information->setImage($newFilename);
            }
    
            $em = $doctrine->getManager();
            $em->persist($information);
            $em->flush();
          

            $this->addFlash('success', 'Votre information a été créée avec succès !');

    
            return $this->redirectToRoute('information_show');
        }
    
        return $this->render('infotmation_educatif/add.html.twig', [
            'formB' => $form->createView(),
        ]);
    }
    

      /**
     * @Route("/Delete/{id}", name="information_delete")
     */
    public function DeleteInformation($id, InformationEducatifRepository $informationEducatifRepository, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $information = $informationEducatifRepository->find($id);
    
        // Check if the entity exists
        if (!$information) {
            throw $this->createNotFoundException('Information not found');
        }
    
        $em->remove($information);
        $em->flush();
        $this->addFlash('error', 'Votre information a été supprimer avec succès !');

    
    
        return $this->redirectToRoute('information_show');
    }
    

    /**
     * @Route("/show", name="information_show")
     */
    public function show(InformationEducatifRepository $informationRepository):Response
    {
        $informations = $informationRepository->findAll();
        return $this->render('infotmation_educatif/show.html.twig',['informations'=>$informations]);

    }

    /**
     * @Route("/list", name="information_list")
     */
    public function list(InformationEducatifRepository $informationRepository):Response
    {
        $informations = $informationRepository->findAll();
        return $this->render('infotmation_educatif/list.html.twig',['informations'=>$informations]);

    }



       /**
     * @Route("/update/{id}", name="information_update")
     */  
    public function UpdateInfo(ManagerRegistry $doctrine, Request $request, InformationEducatifRepository $rep, $id): Response
    {
        // Récupérer l'entité à mettre à jour en utilisant son identifiant
        $information = $rep->find($id);
    
        // Créer un formulaire basé sur cette entité
        $form = $this->createForm(InformationType::class, $information);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier de l'image
            $imageFile = $form->get('image')->getData();
    
            // Vérifier si une nouvelle image a été téléchargée
            if ($imageFile) {
                // Générer un nom unique pour la nouvelle image
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                // Déplacer le fichier vers le répertoire où les images doivent être sauvegardées
                try {
                    $imageFile->move($this->getParameter('images_directory'), $newFilename);
                } catch (FileException $e) {
                    // Gérer l'exception
                }
    
                // Mettre à jour le chemin de l'image dans l'entité
                $information->setImage($newFilename);
            }
    
            // Mettre à jour les autres champs de l'entité
            // ...
    
            // Enregistrer les modifications dans la base de données
            $em = $doctrine->getManager();
            $em->flush();
            $this->addFlash('success', 'Votre information a été modifier avec succès !');

    
            return $this->redirectToRoute('information_show');
        }
    
        return $this->render('infotmation_educatif/update.html.twig', [
            'formB' => $form->createView(),
        ]);
    }
    
    

    /**
 * @Route("/information/{id}", name="information_showId")
 */
public function afficherParId(InformationEducatif $information): Response
{
    return $this->render('infotmation_educatif/details.html.twig', [
        'information' => $information,
    ]);
}
  /**
 * @Route("/like/{id}", name="like_information", methods={"POST"})
 */
public function like(Request $request, InformationEducatif $information): JsonResponse
{
    $likes = $information->getLikes() + 1;
    $information->setLikes($likes);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($information);
    $entityManager->flush();

    return new JsonResponse(['totalLikes' => $likes]);
}

/**
 * @Route("/dislike/{id}", name="dislike_information", methods={"POST"})
 */
public function dislike(Request $request, InformationEducatif $information ,ManagerRegistry $doctrine ): JsonResponse
{
    $dislikes = $information->getDislikes() + 1;
    $information->setDislikes($dislikes);

    $em= $this->$doctrine->getManager();
    $em->persist($information);
    $em->flush();

    return new JsonResponse(['totalDislikes' => $dislikes]);
}

   
    /**
     * @Route("/statistics", name="information_statistics" ,methods={"GET"})
     */
    
    
     public function statistics(InformationEducatifRepository $informationEducatifRepository): Response
     {
         // Récupérer toutes les allergies avec le nombre d'informations éducatives associées
         $informationByAllergie = $informationEducatifRepository->countInformationByAllergie();
 
         // Préparer les données pour le graphique
         $labels = [];
         $data = [];
         foreach ($informationByAllergie as $row) {
             $labels[] = $row['nom']; // Nom de l'allergie
             $data[] = $row['info_count']; // Nombre d'informations éducatives
         }
 
         return $this->render('infotmation_educatif/statistics.html.twig', [
             'labels' => json_encode($labels), // Convertir en JSON pour JavaScript
             'data' => json_encode($data), // Convertir en JSON pour JavaScript
         ]);     }


/**
     * @Route("/repeatedWords_satistcs", name="repeatedWords_statistic" ,methods={"GET"})
     */
    
     public function countRepeatedWordsAction(InformationEducatifRepository $informationRepository): Response
     {
         // Call the countRepeatedWords method from the repository
         $repeatedWords = $informationRepository->countRepeatedWords();
     
         return $this->render('\infotmation_educatif\repeatedWords.html.twig', [
             'repeatedWords' => $repeatedWords,
         ]);
     }
/**
     * @Route("/RepeatedSymptome", name="RepeatedSymptome" ,methods={"GET"})
     */
    public function countRepeatedSymptome(InformationEducatifRepository $informationRepository): Response
{
    // Call the countRepeatedSymptome method from the repository
    $repeatedSymptome = $informationRepository->countRepeatedSymptome();
    return $this->render('infotmation_educatif/Symptome.html.twig', [
        'repeatedSymptome' => $repeatedSymptome,
    ]);
}
/**
 * @Route("/information/pdf/{id}", name="information_pdf", methods={"GET"})
 */

public function generatePdfWithImage(InformationEducatif $information): Response
{
   
    // Récupérez les données de l'entité
    $titre = $information->getTitre();
    $symptome = $information->getSymptome();
    $causes = $information->getCauses();
    $traitement = $information->getTraitement();
    $image = $information->getImage(); // Assurez-vous que l'image est accessible depuis le serveur

    // Créer une nouvelle instance de TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Paramètres d'en-tête/pied de page
    $logo = '/img/logo.png';
    $pdf->setHeaderData($logo , 50,  'Information sur les allergies alimentaires');

    // Configuration des polices
    $pdf->SetFont('helvetica', '', 20);

    // Ajouter une page
    $pdf->AddPage();

    // Ajouter un titre
    $pdf->Cell(0, 30, 'Titre: ' . $titre, 0, 'L');

    // Ajouter une image
    $imageFile = $image; 
    $pdf->Image($imageFile, 15, 50, 180, 160, '', '', '', false, 300, '', false, false, 0);

    // Ajouter du texte
    $pdf->Ln(10);

    $pdf->MultiCell(0, 10, 'Symptôme: ' . $symptome, 0, 'L');
    $pdf->MultiCell(0, 10, 'Causes: ' . $causes, 0, 'L');
    $pdf->MultiCell(0, 10, 'Traitement: ' . $traitement, 0, 'L');

    // Sortie du PDF au navigateur (avec nom de fichier spécifié)
    $pdfFile = 'output.pdf';
    $pdf->Output($pdfFile, 'D');

    // Créez une réponse Symfony avec le contenu PDF et un en-tête de type de contenu approprié
    $response = new Response(file_get_contents($pdfFile));
    $response->headers->set('Content-Type', 'application/pdf');

    // Retourner la réponse
    return $response;}

/**
 * @Route("/send-information-email/{id}", name="send_information_email")
 */
public function sendInformationEmail(EmailSender $emailSender, InformationEducatif $information): RedirectResponse
{
    $recipientEmai = 'guzaineb@email.com';
 
    $emailSender->sendEducationalInformationEmail($recipientEmai, $information);

    $this->addFlash('success', 'L\'e-mail d\'information a été envoyé avec succès !');

    return $this->redirectToRoute('information_show');
}

    
}