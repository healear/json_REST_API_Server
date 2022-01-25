<?php

namespace App\Controller;

use App\Entity\Plans;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlanController extends AbstractController
{

    public function get_plans(Request $request, PlanRepository $planRepository, Security $security): JsonResponse
    {
        if (empty($security->getUser()))
        {
            return new JsonResponse('No user', 400);
        }
        else
        {
            $data = $planRepository->findBy(["user" => $security->getUser()->getUserIdentifier()]);
            return new JsonResponse($data, 200);
        }
    }

    public function add_plan(Request $request, EntityManagerInterface $entityManager, Security $security): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        if (empty($name))
        {
            return new JsonResponse('Empty name', 422);
        }
        $newPlan = new Plans();
        $newPlan->setName($name);
        $newPlan->setUser($security->getUser()->getUserIdentifier());
        $entityManager->persist($newPlan);
        $entityManager->flush();
        return new JsonResponse('A plan was added', 201);
    }

    public function delete_plan(EntityManagerInterface $entityManager, PlanRepository $planRepository, Security $security, $id): JsonResponse
    {
        $plan = $planRepository->find($id);
        if (!$plan)
        {
            return new JsonResponse('No plans by this id', 404);
        }
        if (!$plan->getUser() == $security->getUser()->getUserIdentifier())
        {
            return new JsonResponse('Wrong users plan', 404);
        }
        $entityManager->remove($plan);
        $entityManager->flush();
        return new JsonResponse('Plans by this id were deleted', 200);
    }

    public function update_plan(Request $request, EntityManagerInterface $entityManager, PlanRepository $planRepository, Security $security, $id): JsonResponse
    {
        $plan = $planRepository->find($id);
        if (!$plan)
        {
            return new JsonResponse('No plan by this id', 404);
        }
        if (!$plan->getUser() == $security->getUser()->getUserIdentifier())
        {
            return new JsonResponse('Wrong users plan', 404);
        }
        $data = json_decode($request->getContent(), true);
        $Name = $data['name'];
        if (empty($Name))
        {
            return new JsonResponse('Empty name', 422);
        }
        $plan->setName($data['name']);
        $entityManager->persist($plan);
        $entityManager->flush();
        return new JsonResponse('Plans with this id were updated', 200);
    }
}