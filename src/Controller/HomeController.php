<?php

namespace App\Controller;


use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $mail = new Mail();
        $mail->send('ali.belaidounipro@gmail.com', 'Ali', 'Bonjour, ceci est un test', 'Mon premier mail');


        return $this->render('home/index.html.twig');
    }
}
