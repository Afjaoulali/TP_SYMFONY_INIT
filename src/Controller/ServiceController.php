<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
         /**
     * @Route("services", name="services_list")
     */                             // autowire
     public function serviceList(ServiceRepository $serviceRepository)
     {
         $services = $serviceRepository->findAll();
 
         return $this->render("services_list.html.twig", ['services' => $services]);
     }


    /**
     * @Route("service/{id}", name="service_show")
     */
    public function serviceShow(ServiceRepository $servicetRepository, $id)
    {
        $service = $servicetRepository->find($id);

        return $this->render("service_show.html.twig", ['service' => $service]);
    }

    /**
     * @Route("create/service", name="create_service")
     */
    public function createService(
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $service = new Service();

        // Création du formulaire
        $serviceForm = $this->createForm(ServiceType::class, $service);

        // HandleRequest permet de récupérer les informations rentrées dans le formulaire
        // et de les traiter
        $serviceForm->handleRequest($request);

        if ($serviceForm->isSubmitted() && $serviceForm->isValid()) {
            // la fonction persist va regarder ce que l'on a fait sur service et
            // réaliser le code pour faire le CREATE ou le UPDATE en fonction de l'origine du service 
            $entityManagerInterface->persist($service);
            // la fonction flush enregistre dans la bdd.
            $entityManagerInterface->flush();

            return $this->redirectToRoute('services_list');
        }

        return $this->render('service_form.html.twig', ['serviceForm' => $serviceForm->createView()]);
    }

    /**
     * @Route("update/service/{id}", name="service_update")
     */
    public function serviceUpdate(
        $id,
        ServiceRepository $serviceRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $service = $serviceRepository->find($id);

        $serviceForm = $this->createForm(ServiceType::class, $service);

        $serviceForm->handleRequest($request);

        if ($serviceForm->isSubmitted() && $serviceForm->isValid()) { 
            $entityManagerInterface->persist($service);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('services_list');
        }

        return $this->render('service_form.html.twig', ['serviceForm' => $serviceForm->createView()]);
    }

    /**
     * @Route("delete/service/{id}", name="delete_service")
     */
    public function deleteService(
        $id,
        EntityManagerInterface $entityManagerInterface,
        ServiceRepository $serviceRepository
    ) {
        $service = $serviceRepository->find($id);

        // remove supprime le service
        $entityManagerInterface->remove($service);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('services_list');
    }
}
