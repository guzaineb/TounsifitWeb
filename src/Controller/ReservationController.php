<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\RestaurantRepository;
use App\Services\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
 
    #[Route('/new/{id}', name: 'reservation_add', methods: ['GET', 'POST'])]
    public function new($id, MailerService $mail, Request $request, EntityManagerInterface $entityManager, RestaurantRepository $restaurantRepository, SessionInterface $session): Response
    { 
        $restaurant = $restaurantRepository->find($id);

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            $mail->send($reservation->getEmail(), "Reservation confirmé", " Félicitation " . $reservation->getNom(). "<br/>" . "Date: " . $reservation->getDateReservation()->format('Y-m-d'));

            $this->addFlash('success', 'La réservation a été ajoutée avec succès.');
            return $this->redirectToRoute('reservation_show', ['id' => $reservation->getId()]);
        }
    
        return $this->renderForm('reservation/add-front.html.twig', [
            'reservation' => $reservation,
            'restaurant' => $restaurant,
            'formR' => $form,
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="reservation_delete", methods={"DELETE"})
     */
    public function deleteReservation($id, ReservationRepository $reservationRepository, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Réservation introuvable');
        }

        $em->remove($reservation);
        $em->flush();

        return $this->redirectToRoute('reservation_show');
    }

    /**
     * @Route("/show", name="reservation_show", methods={"GET"})
     */
    public function show(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();
        return $this->render('Reservation/show.html.twig', ['reservations' => $reservations]);
    }

    /**
     * @Route("/update/{id}", name="reservation_update", methods={"GET","POST"})
     */
    public function updateReservation(ManagerRegistry $doctrine, Request $request, ReservationRepository $reservationRepository, $id): Response
    {
        $reservation = $reservationRepository->find($id);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            return $this->redirectToRoute('reservation_show', ['id' => $reservation->getId()]);
        }
    
        return $this->render('Reservation/update.html.twig', [
            'formR' => $form->createView(),
        ]);
    }

    /**
     * @Route("/grid", name="reservation_grid", methods={"GET"})
     */
    public function showGrid(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();
        return $this->render('Reservation/grid.html.twig', ['reservations' => $reservations]);
    }

    /**
     * @Route("/details/{id}", name="reservation_details", methods={"GET"})
     */
    public function reservationDetails($id, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->find($id);

        return $this->render('Reservation/details.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
