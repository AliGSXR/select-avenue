<?php

namespace App\Controller\Account;

use App\Classe\Cart;
use App\Entity\Adress;
use App\Form\AdressUserType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class AdressControler extends AbstractController
{
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/compte/adresses', name: 'app_account_adresses')]
    public function index(): Response
    {
        return $this->render('account/adress/index.html.twig');
    }

    #[Route('/compte/adresses/delete/{id}', name: 'app_account_adress_delete')]
    public function delete($id, AdressRepository $adressRepository, Request $request): Response
    {
        $adress = $adressRepository->find($id);

        // Vérification que l'adresse appartient bien à l'utilisateur connecté
        if (!$adress || $adress->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account_adresses');
        }
        $submittedToken = $request->query->get('_token');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete_adress' . $id, $submittedToken))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $this->addFlash('success', "Votre adresse a bien été supprimée");

        $this->entityManager->remove($adress);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_account_adresses');
    }

    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_adress_form', defaults: ['id' => null])]
    public function form(Request $request, $id, AdressRepository $adressRepository, Cart $cart): Response
    {
        if ($id) {
            $adress = $adressRepository->find($id);
            if (!$adress || $adress->getUser() !== $this->getUser()) {
                return $this->redirectToRoute('app_account_adresses');
            }
        } else {
            $adress = new Adress();
            $adress->setUser($this->getUser());
        }

        $form = $this->createForm(AdressUserType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($adress);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre adresse a bien été enregistrée");

            if ($cart->fullQuantity() > 0) {
                return $this->redirectToRoute('app_order');
            }

            return $this->redirectToRoute('app_account_adresses');
        }
        return $this->render('account/adress/form.html.twig', [
            'adressForm' => $form->createView(),
        ]);
    }
}