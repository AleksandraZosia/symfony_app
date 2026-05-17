<?php

namespace App\Controller;

use App\Repository\DataRepository;
use App\Entity\Data;
use App\Entity\User;
use App\Form\DataType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function index(DataRepository $dataRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $data_add = new Data();
        $data_add->setUser($this->getUser());
        $form = $this->createForm(DataType::class, $data_add);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data_add->setDate(new \DateTimeImmutable());
            $entityManager->persist($data_add);
            $entityManager->flush();

            // Return the newly created data as JSON to update the DOM
            return new JsonResponse([
                'success' => true,
                'newData' => [
                    'product' => $data_add->getProduct(),
                    'date'    => $data_add->getDate()->format('Y-m-d'),
                    'color'   => $data_add->getColor(),
                    'amount'  => $data_add->getAmount(),
                    'id'    => $data_add->getId(),
                    'user' => $data_add->getUser()->getLogin(),
                ]
            ]);
        }

        // If the form fails validation on AJAX request
        if ($form->isSubmitted() && !$form->isValid()) {
            return new JsonResponse(['success' => false, 'errors' => 'Invalid data form'], 400);
        }

        $data = $dataRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'data' => $data,
            'form' =>$form->createView()
        ]);
    }
}
