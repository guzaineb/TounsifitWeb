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
use Stripe\BillingPortal\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
 * @Route("/show/{id}", name="reservation_show", methods={"GET"})
 */
public function showReservationDetails($id, ReservationRepository $reservationRepository): Response
{
    $reservation = $reservationRepository->find($id);

    if (!$reservation) {
        throw $this->createNotFoundException('Réservation introuvable');
    }

    return $this->render('Reservation/show.html.twig', [
        'reservation' => $reservation,
    ]);
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
    /**
 * @Route("/pay/{id}", name="reservation_pay", methods={"POST"})
 */
public function payReservation($id, ReservationRepository $reservationRepository): Response
{
    // Logique de paiement ici

    // Vous devez renvoyer une réponse appropriée après le traitement du paiement
    // Par exemple, redirigez l'utilisateur vers une page de confirmation de paiement
    return $this->redirectToRoute('payment_confirmation'); // Assurez-vous d'avoir une route nommée 'payment_confirmation' définie dans votre configuration de routes
}

    
#[Route('/checkout', name: 'checkout', methods: ['POST'])]
    public function checkout(): Response
    {
        Stripe::setApikey('sk_test_51PBuihRttdtQcrdJqTIwBv99Nw3DhUEMvnrMgGhqpYPVZRdKPMwIwXPioOUofNNhAx0GmigM7M5tuJFgmlBemqNV006CV93HhQ');

        // Create a checkout session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'USD',
                    'product_data' => [
                        'name' => 'ethereumm',
                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url'  => $this->generateUrl('success_url', [],UrlGeneratorInterface::ABSOLUTE_URL),           
            'cancel_url'  => $this->generateUrl('cancel_url', [],UrlGeneratorInterface::ABSOLUTE_URL),

        ]);
        

        return $this->redirect($session->url, 303);
    
    }

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        
        return $this->render('Paiement/success.html.twig');
    
    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        
        return $this->render('Paiement/cancel.html.twig');
        
    
    }



}
