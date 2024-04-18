<?php

namespace App\Controller;

use App\Entity\InformationEducatif;
use App\Form\InformationType;
use App\Repository\InformationEducatifRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    
            return $this->redirectToRoute('information_show');
        }
    
        return $this->render('infotmation_educatif/update.html.twig', [
            'formB' => $form->createView(),
        ]);
    }
    
    

           /**
     * @Route("/{id}", name="restaurant_show")
     */
    public function afficherParId(InformationEducatif $info): Response
    {
        return $this->render('infotmation_educatif/detais.html.twig', [
            'infotmation_educatif' => $info,
        ]);
    }
     /**
     * @Route("/orderByTitre", name="orderByTitre" ,methods={"GET"})
     */
    public function orderByTitre(Request $request,InformationEducatifRepository $informationEducatifRepository): Response
    {

        //list of students order By Dest
        $informationByTitre = $informationEducatifRepository->orderByTitre();

        return $this->render('infotmation_educatif/show.html.twig', [
            'infotmation_educatif' => $informationByTitre,
        ]);

        

    }

}
