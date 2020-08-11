<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class HealthcheckController extends Controller
{
    /**
     * @Route("/ping", name="healthcheck")
     */
    public function index()
    {
        return new JsonResponse("pong");
    }
}
