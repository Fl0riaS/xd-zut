<?php

namespace App\Command;

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
    public function __construct(private MailerInterface $mailer)
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
       /* $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($input->getOption('option1')) {
            // ...
        }*/
        $email = (new Email())
        ->from('xdzut@interia.pl')
        ->to('xdzut@interia.pl')
        ->subject('Raport')
        ->html('<p>Raport ponizej:</p>');
        $email->addPart(new DataPart(new File(__DIR__.'\..\..\raport.txt')));

        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        $this->mailer->send($email);

        return Command::SUCCESS;
    }
}
