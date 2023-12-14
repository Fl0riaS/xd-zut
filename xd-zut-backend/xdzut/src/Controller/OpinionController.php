<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Annotation\MapRequestPayload;
use App\Model\AddOpinionDTO;

class OpinionController extends AbstractController
{
  #[Route('/opinion', name: 'app_opinion')]
  public function index(): JsonResponse
  {
    return $this->json([
      'message' => 'Welcome to your new controller!',
      'path' => 'src/Controller/OpinionController.php',
    ]);
  }

  // Add opinion
  // Backend get
  // score
  // startDate
  // endDate
  // workerTitle
  // lessonFormShort
  // groupName
  // comment
  #[Route('/opinion/add', name: 'app_opinion_add', methods: ['POST'])]
  public function addOpinion(
    Request $request,
    // SerializerInterface $serializer,
    #[MapRequestPayload] AddOpinionDTO $opinionDTO
  ): JsonResponse {

    // get data from request
    $data = json_decode($request->getContent(), true);

    return $this->json([
      "data" => $data
    ]);
  }
}
