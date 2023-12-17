<?php

namespace App\Services;

use App\Entity\Raport;
use App\Repository\RaportRepository;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class RaportService
{
    public function __construct(RaportRepository $raportRepository)
    {
        $this->raportRepository = $raportRepository;
    }

    public function generateSheet(Raport $raport): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ustawianie danych raportu
        $sheet->setCellValue('A1', 'Raport');
        $sheet->setCellValue('A2', 'Data');
        $sheet->setCellValue('B2', $raport->getDate()->format('Y-m-d'));
        $sheet->setCellValue('A3', 'Prowadzący');
        $sheet->setCellValue('B3', $raport->getCourse()->getTeacher()->getName());
        $sheet->setCellValue('A4', 'Przedmiot');
        $sheet->setCellValue('B4', $raport->getCourse()->getSubject()->getTitle());
        $sheet->setCellValue('A5', 'Grupa');
        $sheet->setCellValue('B5', $raport->getCourse()->getGroupName());
        $sheet->setCellValue('A6', 'Ocena miesiąca');
        $sheet->setCellValue('B6', $raport->getTotalScore());

        // Ocena z ostatnich zajec to suma wszystkich ocen z tego raportu
        $last_lecture_score = 0;

        foreach ($raport->getOpinions() as $opinion) {
            $last_lecture_score += $opinion->getScore();
        }

        $sheet->setCellValue('A7', 'Ocena całkowita');
        $sheet->setCellValue('B7', $raport->getMonthScore());
        $sheet->setCellValue('A8', 'Ocena z ostatnich zajęć');
        $sheet->setCellValue('B8', $last_lecture_score);

        // Ustawianie opinii
        $sheet->setCellValue('A10', 'Opinie');
        // $sheet->setCellValue('A9', 'Data');
        $sheet->setCellValue('B11', 'Ocena');
        $sheet->setCellValue('C11', 'Komentarz');

        $row = 12;
        foreach ($raport->getOpinions() as $opinion) {
            // $sheet->setCellValue('A' . $row, $opinion->getDate()->format('Y-m-d'));
            $sheet->setCellValue('B' . $row, $opinion->getScore());
            $sheet->setCellValue('C' . $row, $opinion->getComment());
            $row++;
        }

        return $spreadsheet;
    }

    public function sendRaport(Raport $raport, MailerInterface $mailer): void
    {
        $spreadsheet = $this->generateSheet($raport);

        // add raport to email
        $writer = new Xlsx($spreadsheet);
        $writer->save('raport.xlsx');

        // send email

        $teacher_email = $raport->getCourse()->getTeacher()->getEmail();

        $email = (new Email())
            ->from('xdzut@interia.pl')
            ->to($teacher_email)
            ->subject('Raport')
            ->text('Raport')
            ->attachFromPath('raport.xlsx');


        $mailer->send($email);

        // delete raport
        unlink('raport.xlsx');
    }
}
