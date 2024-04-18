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
use App\Entity\User; 



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
    public function DeleteAllergie($id, AllergieRepository $allergieRepository, ManagerRegistry $doctrine): Response
    {
    $em= $doctrine->getManager();
    $allergie= $allergieRepository->find($id);

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
     * @Route("/affiche", name="allergie_affiche")
     */
    public function affiche(AllergieRepository $allergieRepository):Response
    {
        $Allergies = $allergieRepository->findAll();
        return $this->render('allergie/feauter.html.twig',['allergies'=>$Allergies]);

        
    }
     /**
     * @Route("/detais/{id}", name="allergie_detais")
     */
    public function detais(AllergieRepository $allergieRepository,$id):Response
    {
        $Allergies = $allergieRepository->findBy($id);
        return $this->render('allergie/detais.html.twig',['allergies'=>$Allergies]);

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



}
