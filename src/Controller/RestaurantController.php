<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/restaurant")
 */
class RestaurantController extends AbstractController
{
    #[Route('/info', name: 'app_restaurant')]
    public function index(): Response
    {
        return $this->render('Restaurant/index.html.twig', [
            'controller_name' => 'RestaurantController',
        ]);
    } 
    
    #[Route('/new', name: 'restaurant_add', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('img')->getData();
            $fileName = uniqid().'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directorys'), $fileName);
            $restaurant->setImg($fileName);
            
            $entityManager->persist($restaurant);
            $entityManager->flush();
    
            // Ajouter le message flash de succès
            $this->addFlash('success', 'Le restaurant a été ajouté avec succès.');
            return $this->redirectToRoute('restaurant_show', ['id' => $restaurant->getId()]);
        }
    
        // Affichage du formulaire
        return $this->renderForm('Restaurant/add.html.twig', [
            'restaurant' => $restaurant,
            'formR' => $form,
        ]);
    }
    
    
    /**
     * @Route("/delete/{id}", name="restaurant_delete")
     */
    public function DeleteRestaurant($id, RestaurantRepository $restaurantRepository, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $restaurant = $restaurantRepository->find($id);

        // Check if the entity exists
        if (!$restaurant) {
            throw $this->createNotFoundException('Restaurant not found');
        }

        $em->remove($restaurant);
        $em->flush();

        return $this->redirectToRoute('restaurant_show');
    }

    /**
     * @Route("/show", name="restaurant_show")
     */
    public function show(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();
        return $this->render('Restaurant/show.html.twig', ['restaurants' => $restaurants]);
    }

    /**
     * @Route("/update/{id}", name="restaurant_update")
     */
    public function UpdateRestaurant(ManagerRegistry $doctrine, Request $request, RestaurantRepository $restaurantRepository, $id): Response
    {
        $restaurant = $restaurantRepository->find($id);
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement de l'image
            $file = $form->get('img')->getData();
            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('images_directorys'), $fileName);
                $restaurant->setImg($fileName);
            }
    
            $em = $doctrine->getManager();
            $em->persist($restaurant);
            $em->flush();
    
            return $this->redirectToRoute('restaurant_show', ['id' => $restaurant->getId()]);
        }
    
        return $this->render('Restaurant/update.html.twig', [
            'formR' => $form->createView(),
        ]);
    }

    /**
    * @Route("/grid", name="restaurant_grid")
    */
    public function showGrid(RestaurantRepository $restaurantRepository): Response
    {
     $restaurants = $restaurantRepository->findAll();
     return $this->render('Restaurant/grid.html.twig', ['restaurants' => $restaurants]);
      }
    /**
    * @Route("/details/{id}", name="restaurant_details")
    */
    public function restaurantDetails($id, RestaurantRepository $restaurantRepository): Response
   {
    $restaurant = $restaurantRepository->find($id);

     return $this->render('Restaurant/details.html.twig', [
        'restaurant' => $restaurant,
     ]);
   }
}
