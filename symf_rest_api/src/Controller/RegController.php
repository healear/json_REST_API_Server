<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RegController extends AbstractController
{

    public function register(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): JsonResponse
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        if (empty($username) || empty($password) ){
            return new JsonResponse("Invalid Username or Password or Email", 422);
        }

        if ($userRepository->findBy(["username"=>$username])!=null){
            return new JsonResponse("User Already Exists", 404);
        }

        $user = new User($username);
        $user->setPassword($encoder->hashPassword($user, $password));
        $user->setUsername($username);
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse(sprintf('User %s successfully created', $user->getUserIdentifier()), 201);
    }

    public function api(): Response
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

}