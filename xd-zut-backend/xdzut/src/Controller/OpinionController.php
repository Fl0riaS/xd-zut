<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Opinion;
use App\Entity\Raport;
use App\Entity\Teacher;
use App\Entity\Subject;
use App\Repository\CourseRepository;
use App\Repository\RaportRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use App\Repository\OpinionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\AddOpinionDTO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;


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
    // score
    // startDate
    // endDate
    // workerTitle
    // lessonFormShort
    // groupName
    // comment
    #[Route('/opinion/add', name: 'app_opinion_add', methods: ['POST'])]
    public function addOpinion(
        #[MapRequestPayload] AddOpinionDTO $opinionDTO,
        RaportRepository                   $raportRepository,
        TeacherRepository                  $teacherRepository,
        CourseRepository                   $courseRepository,
        SubjectRepository                  $subjectRepository,
        OpinionRepository                  $opinionRepository
    ): JsonResponse
    {
        $teacherName = $opinionDTO->workerTitle;
        $teacher = $teacherRepository->findOneBy(['name' => $teacherName]);
        if (!$teacher) {
            $teacher = new Teacher();
            $teacher->setName($teacherName);
            $teacherRepository->save($teacher);
        }

        $subjectTitle = $opinionDTO->title;
        $subject = $subjectRepository->findOneBy(['title' => $subjectTitle]);
        if (!$subject) {
            $subject = new Subject();
            $subject->setTitle($subjectTitle);
            $subjectRepository->save($subject);
        }

        $course = new Course();
        if (!$subject->getId() || !$teacher->getId()) {
            $course->setSubject($subject);
            $course->setTeacher($teacher);
            $course->setGroupName($opinionDTO->groupName);
            $courseRepository->save($course, true);
        } else {
            $course = $courseRepository->findOneBy(
                ['groupName' => $opinionDTO->groupName, 'subject' => $subject->getId(), 'teacher' => $teacher->getId()]
            );
        }

        $raport = $raportRepository->findOneBy(
            ['course' => $course->getId(), 'date' => $opinionDTO->endDate]
        );

        if (!$raport) {
            $raport = new Raport();
            $raport->setCourse($course);
            $raport->setDate($opinionDTO->endDate);
            $raport->setGenerateIn($opinionDTO->endDate->add(new \DateInterval('PT30M')));

            $previousRaport = $raportRepository->findOneBy(
                ['course' => $course->getId()],
                ['date' => 'DESC']
            );
            if ($previousRaport) {
                $raport->setTotalScore($previousRaport->getTotalScore());
                if ($previousRaport->getDate()->format('m') == $opinionDTO->endDate->format('m')) {
                    $raport->setMonthScore($previousRaport->getMonthScore());
                } else {
                    $raport->setMonthScore(0);
                }
            } else {
                $raport->setTotalScore(0);
                $raport->setMonthScore(0);
            }
        }

        $response = new JsonResponse();
        if ($raport->getGenerateIn() < new \DateTime()) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setContent('Can not add opinion to the raport. Raport is already generated.');
            return $response;
        }

        $raport->setTotalScore($raport->getTotalScore() + $opinionDTO->score);
        $raport->setMonthScore($raport->getMonthScore() + $opinionDTO->score);
        $raportRepository->save($raport);

        $opinion = new Opinion();
        $opinion->setRaport($raport);
        $opinion->setScore($opinionDTO->score);
        $opinion->setComment($opinionDTO->comment);

        $opinionRepository->save($opinion, true);

        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

  #[Route('/excel/generate', name: 'app_excel_create', methods: ['GET'])]
  public function generateExcel(MailerInterface $mailer): Response
  {
      // $spreadsheet = new Spreadsheet();
      // $sheet = $spreadsheet->getActiveSheet();

      // $data = [
          // ['Header1', 'Header2', 'Header3'],
          // [1, 2, 3],
          // [4, 5, 6],
          // ...
      // ];

      // $mailer->SMTPDebug = SMTP::DEBUG_SERVER;


      // Send email to uf49430@zut.edu.pl
      $email = (new Email())
          ->from('xdzut@interia.pl')
          ->to('xdzut@interia.pl')
          ->subject('Test')
          ->text('Sending emails is fun again!')
          ->html('<p>See Twig integration for better HTML integration!</p>');

      try{
        $mailer->send($email);
        // Catch any errors including failed validations
        echo 'Email sent!';
      } catch (TransportExceptionInterface $e) {
        echo $e->getMessage();
      }

      // Check if mail has been sent
      

      // Adding data to spreadsheet
      // $sheet->fromArray($data, null, 'A1');

      // Create writer and save to file
      // $writer = new Xlsx($spreadsheet);

      // Create http response
      // $response = new StreamedResponse(function() use ($writer) {
      //     $writer->save('php://output');
      // });

      // // Setting up correct headers
      // $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      // $response->headers->set('Content-Disposition', 'attachment;filename="export.xlsx"');
      // $response->headers->set('Cache-Control','max-age=0');
      // return $response;

      // return json
      return $this->json([
        'message' => 'Welcome to your new controller!',
        'path' => 'src/Controller/OpinionController.php',
      ]);
  }
}
