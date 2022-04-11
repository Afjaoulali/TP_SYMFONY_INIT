<?php

namespace App\Controller;
use App\Entity\Employes;
use App\Form\EmployeeType;
use App\Repository\EmployesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'app_employee')]
    public function index(): Response
    {
        return $this->render('employee/index.html.twig', [
            'controller_name' => 'EmployeeController',
        ]);
    }

    /**
     * @Route("employes", name="employes_list")
     */                             // autowire
     public function employeeList(EmployesRepository $employesRepository)
     {
         $employes = $employesRepository->findAll();
 
         return $this->render("employes_list.html.twig", ['employes' => $employes]);
     }

     
     /**
     * @Route("employee/{id}", name="employee_show")
     */
    public function employeeShow(EmployesRepository $employesRepository, $id)
    {
        $employee = $employesRepository->find($id);

        return $this->render("employee_show.html.twig", ['employee' => $employee]);
    }

    /**
     * @Route("create/employee", name="create_employee")
     */
    public function createEmployee(
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $employee = new Employes();

        // Création du formulaire
        $employeeForm = $this->createForm(EmployeeType::class, $employee);

        // HandleRequest permet de récupérer les informations rentrées dans le formulaire
        // et de les traiter
        $employeeForm->handleRequest($request);

        if ($employeeForm->isSubmitted() && $employeeForm->isValid()) {
            // la fonction persist va regarder ce que l'on a fait sur employes et
            // réaliser le code pour faire le CREATE ou le UPDATE en fonction de l'origine de l'employes  
            $entityManagerInterface->persist($employee);
            // la fonction flush enregistre dans la bdd.
            $entityManagerInterface->flush();

            return $this->redirectToRoute('employes_list');
        }

        return $this->render('employee_form.html.twig', ['employeeForm' => $employeeForm->createView()]);
    }

    /**
     * @Route("update/employee/{id}", name="employee_update")
     */
    public function employeeUpdate(
        $id,
        EmployesRepository $employesRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $employee = $employesRepository->find($id);

        $employeeForm = $this->createForm(EmployeeType::class, $employee);

        $employeeForm->handleRequest($request);

        if ($employeeForm->isSubmitted() && $employeeForm->isValid()) { 
            $entityManagerInterface->persist($employee);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('employes_list');
        }

        return $this->render('employee_form.html.twig', ['employeeForm' => $employeeForm->createView()]);
    }

    /**
     * @Route("delete/employee/{id}", name="delete_employee")
     */
    public function deleteemployee(
        $id,
        EntityManagerInterface $entityManagerInterface,
        EmployesRepository $employesRepository
    ) {
        $employee = $employesRepository->find($id);

        // remove supprime l'employee
        $entityManagerInterface->remove($employee);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('employes_list');
    }

    
}
