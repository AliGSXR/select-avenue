<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\OrderType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {

        $adresses = $this->getUser()->getAdresses();

        if (count($adresses) == 0) {
            return $this->redirectToRoute('app_account_adress_form');
        }

        $form = $this->createForm(OrderType::class, null, [
            'adresses' => $adresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);
        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(\Symfony\Component\HttpFoundation\Request $request, Cart $cart): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'adresses' => $this->getUser()->getAdresses()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }



        return $this->render('order/summary.html.twig', [
            'choices'=>$form->getData(),
            'cart'=>$cart->getCart(),
            'totalWt'=>$cart->getTotalWt(),
        ]);
    }

}
