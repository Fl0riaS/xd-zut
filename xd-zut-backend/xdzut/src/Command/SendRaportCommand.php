<?php

namespace App\Command;

use App\Repository\RaportRepository;
use App\Services\RaportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;


use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

#[AsCommand(
    name: 'send:raport',
    description: 'Command sending email with report attached',
)]
class SendRaportCommand extends Command
{
    public function __construct(private MailerInterface $mailer, private RaportRepository $raportRepository, private RaportService $raportService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $raports = $this->raportRepository->findRaportsToSend();
        printf("Found %d raports to send\n", count($raports));
        foreach($raports as $raport) {
            if ($raport->getCourse()->getTeacher()->getEmail() === null) {
                continue;
            }
            $this->raportService->sendRaport($raport, $this->mailer);
            $raport->isSent = true;
            $this->raportRepository->save($raport, true);
        }
        return Command::SUCCESS;
    }
}
