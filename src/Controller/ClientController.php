<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
     /**
     * @Route("clients", name="clients_list")
     */                             // autowire
     public function clientList(ClientRepository $clientRepository)
     {
         $clients = $clientRepository->findAll();
 
         return $this->render("clients_list.html.twig", ['clients' => $clients]);
     }


    /**
     * @Route("client/{id}", name="client_show")
     */
    public function clientShow(ClientRepository $clientRepository, $id)
    {
        $client = $clientRepository->find($id);

        return $this->render("client_show.html.twig", ['client' => $client]);
    }

    /**
     * @Route("create/client", name="create_client")
     */
    public function createClient(
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $client = new Client();

        // Création du formulaire
        $clientForm = $this->createForm(ClientType::class, $client);

        // HandleRequest permet de récupérer les informations rentrées dans le formulaire
        // et de les traiter
        $clientForm->handleRequest($request);

        if ($clientForm->isSubmitted() && $clientForm->isValid()) {
            // la fonction persist va regarder ce que l'on a fait sur client et
            // réaliser le code pour faire le CREATE ou le UPDATE en fonction de l'origine du client 
            $entityManagerInterface->persist($client);
            // la fonction flush enregistre dans la bdd.
            $entityManagerInterface->flush();

            return $this->redirectToRoute('clients_list');
        }

        return $this->render('client_form.html.twig', ['clientForm' => $clientForm->createView()]);
    }

    /**
     * @Route("update/client/{id}", name="client_update")
     */
    public function clientUpdate(
        $id,
        ClientRepository $clientRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $client = $clientRepository->find($id);

        // Création du formulaire
        $clientForm = $this->createForm(ClientType::class, $client);

        $clientForm->handleRequest($request);

        if ($clientForm->isSubmitted() && $clientForm->isValid()) { 
            $entityManagerInterface->persist($client);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('clients_list');
        }

        return $this->render('client_form.html.twig', ['clientForm' => $clientForm->createView()]);
    }

    /**
     * @Route("delete/client/{id}", name="delete_client")
     */
    public function deleteclient(
        $id,
        EntityManagerInterface $entityManagerInterface,
        ClientRepository $clientRepository
    ) {
        $client = $clientRepository->find($id);

        // remove supprime le client
        $entityManagerInterface->remove($client);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('clients_list');
    }
}
