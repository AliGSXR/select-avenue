<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function add(\Symfony\Component\HttpFoundation\Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_card');
        }

        $products = $cart->getCart();
        $form = $this->createForm(OrderType::class, null, [
            'adresses' => $this->getUser()->getAdresses()
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $adressObj = $form->get('adresses')->getData();

            $adress = $adressObj->getFirstname() . ' ' . $adressObj->getLastname().'<br>';
            $adress .= $adressObj->getAdress().'<br>';
            $adress .= $adressObj->getPostal().' '.$adressObj->getCity().'<br>';
            $adress .= $adressObj->getCountry().'<br>';
            $adress .= $adressObj->getPhone();


            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($adress);

            foreach ($products as $product) {
                $orderDetail = new OrderDetail();
                $orderDetail ->setProductName($product['object']->getName());
                $orderDetail ->setProductIllustration($product['object']->getIllustration());
                $orderDetail ->setProductPrice($product['object']->getPrice());
                $orderDetail ->setProductQuantity($product['quantity']);
                $orderDetail ->setProductTva($product['object']->getTva());
                $order->addOrderDetail($orderDetail);

            }
            $entityManager->persist($order);
            $entityManager->flush();
        }
        return $this->render('order/summary.html.twig', [
            'choices'=>$form->getData(),
            'cart'=>$products,
            'order'=>$order,
            'totalWt'=>$cart->getTotalWt(),
        ]);
    }

}
