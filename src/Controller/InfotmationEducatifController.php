<?php

namespace App\Controller;

use App\Entity\InformationEducatif;
use App\Form\InformationType;
use App\Repository\InformationEducatifRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $form=$this->createForm(InformationType::class,$information);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->persist($information);
            $em->flush();
            return $this-> redirectToRoute('information_show');
        }
        return $this->render('infotmation_educatif/add.html.twig',[
            'formB'=>$form->createView(),
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
     * @Route("/update/{id}", name="information_update")
     */  
      public function UpdateAllergie(ManagerRegistry $doctrine, Request $request, InformationEducatifRepository $rep, $id): Response
    {
       $information = $rep->find($id);
       $form=$this->createForm(informationType::class,$information);
       $form->handleRequest($request);
       if($form->isSubmitted()){
           $em= $doctrine->getManager();
           $em->persist($information);
           $em->flush();
           return $this-> redirectToRoute('information_show');
       }
       return $this->render('infotmation_educatif/update.html.twig',[
           'formB'=>$form->createView(),
        ]);
       }

}
