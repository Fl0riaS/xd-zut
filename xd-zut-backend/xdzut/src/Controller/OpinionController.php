<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Annotation\MapRequestPayload;
use App\Model\AddOpinionDTO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;


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
      ): JsonResponse
    {

        // get data from request
        $data = json_decode($request->getContent(), true);

        return $this->json([
          "data" => $data
        ]);
        
    }

  #[Route('/excel/generate', name: 'app_excel_create', methods: ['GET'])]
  public function generateExcel(): Response
  {
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $data = [
          ['Header1', 'Header2', 'Header3'],
          [1, 2, 3],
          [4, 5, 6],
          // ...
      ];

      // Adding data to spreadsheet
      $sheet->fromArray($data, null, 'A1');

      // Create writer and save to file
      $writer = new Xlsx($spreadsheet);

      // Create http response
      $response = new StreamedResponse(function() use ($writer) {
          $writer->save('php://output');
      });

      // Setting up correct headers
      $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      $response->headers->set('Content-Disposition', 'attachment;filename="export.xlsx"');
      $response->headers->set('Cache-Control','max-age=0');
      return $response;
  }
}
