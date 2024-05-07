<?php

namespace App\Controller;

use App\Entity\Allergie;
use App\Form\AllergieType;
use App\Repository\AllergieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



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
     
    public function AddAllergie(ManagerRegistry $doctrine, Request $request): Response
    {
     
        $allergie = new Allergie();
        $form=$this->createForm(AllergieType::class,$allergie);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->persist($allergie);
            $em->flush();
            return $this-> redirectToRoute('allergie_show');
        }
        return $this->render('allergie/new.html.twig',[
            'formA'=>$form->createView(),
        ]);
    }

      /**
     * @Route("/Delete/{id}", name="Allergie_delete")
     */
    public function DeleteAllergie($id, AllergieRepository $rep, ManagerRegistry $doctrine): Response
    {
    $em= $doctrine->getManager();
    $allergie= $rep->find($id);
    $em->remove($allergie);
    $em->flush();
    return $this->redirectToRoute('allergie_show');
}

       /**
     * @Route("/show", name="allergie_show")
     */
    public function show(AllergieRepository $allergieRepository):Response
    {
        $Allergies = $allergieRepository->findAll();
        return $this->render('allergie/show.html.twig',['allergies'=>$Allergies]);

    }
       /**
     * @Route("/update/{id}", name="allergie_update")
     */  
      public function UpdateAllergie(ManagerRegistry $doctrine, Request $request, AllergieRepository $rep, $id): Response
    {
       $allergie = $rep->find($id);
       $form=$this->createForm(AllergieType::class,$allergie);
       $form->handleRequest($request);
       if($form->isSubmitted()){
           $em= $doctrine->getManager();
           $em->persist($allergie);
           $em->flush();
           return $this-> redirectToRoute('allergie_show');
       }
       return $this->render('allergie/update.html.twig',[
           'formA'=>$form->createView(),
        ]);
       }

}
