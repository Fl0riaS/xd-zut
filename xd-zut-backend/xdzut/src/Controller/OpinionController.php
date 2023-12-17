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
use App\Services\RaportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\AddOpinionDTO;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
    ): JsonResponse {
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

    #[Route('/raports', name: 'app_reports_get_all', methods: ['GET'])]
    public function getRaports(RaportRepository $raportRepository): JsonResponse
    {
        $raports = $raportRepository->findAll();

        // Return raports as json, use serialization, but be careful with circular references
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $json = $serializer->serialize($raports, 'json', [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
            return $object->getId();
        }]);

        // return json response
        return new JsonResponse($json, Response::HTTP_OK, [], true); // true indicates $json is already a JSON string
    }

    // Generate excel from raport
    #[Route('/raport/{id}/generate', name: 'app_raport_generate', methods: ['GET'])]
    public function generateRaport(Raport $raport, RaportService $raportService): StreamedResponse
    {
        $spreadsheet = $raportService->generateSheet($raport);

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="raport.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    // Endpoint for resend email with raport
    #[Route('/raport/{id}/resend', name: 'app_raport_resend', methods: ['GET'])]
    public function resendRaport(Raport $raport, MailerInterface $mailer, RaportService $raportService): JsonResponse
    {

        $raportService->sendRaport($raport, $mailer);
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_OK);

        return $response;

    }
}
