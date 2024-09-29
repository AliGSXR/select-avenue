<?php

namespace App\Classe;

use Doctrine\Bundle\DoctrineBundle\Mapping\EntityListenerServiceResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use function mysql_xdevapi\getSession;

class Cart
{

    public function __construct(private RequestStack $requestStack)
    {

    }

    /*
     * add()
     * Fonction permettant l'ajout d'un produit au panier
     */
    public function add($product)
    {

        $cart = $this->requestStack->getSession()->get('cart');

        if (isset ($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => $cart[$product->getId()]['quantity'] + 1,
            ];
        }
        else {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => 1,
            ];
        }


        $this->requestStack->getSession()->set('cart', $cart );

    }

    /*
     * fullQuantity()
     * Fonction retournant le nombre total de produit au panier
     */

    public function fullQuantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $qty = 0;

        if(!isset($cart)){
            return $qty;
        }
        foreach ($cart as $product) {
            $qty = $qty + $product['quantity'];
        }
        return $qty;
    }

    /*
     * getTotalWt()
     * Fonction retournant le prix total au panier
     */
    public function getTotalWt(){
        $cart = $this->requestStack->getSession()->get('cart');
        $price = 0;

        if(!isset($cart)){
            return $price;
        }

        foreach ($cart as $product) {
            $price = $price + ($product['object']->getPriceWt() * $product['quantity']);
        }
        return $price;
    }

    /*
     * add()
     * Fonction permettant la suppression d'un produit au panier
     */
    public function decrease($id){
        $cart = $this->requestStack->getSession()->get('cart');

        if($cart[$id]['quantity'] > 1){
            $cart[$id]['quantity']--;
        }
        else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart );

    }

    /*
     * remove()
     * Fonction permettant l'effacement du panier
     */
    public function remove(){
       return $this->requestStack->getSession()->remove('cart');
    }

    /*
     * getCart()
     * Fonction retournant le panier
     */
    public function getCart(){

        return $this->requestStack->getSession()->get('cart');

    }
}