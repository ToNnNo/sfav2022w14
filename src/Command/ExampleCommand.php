<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class ExampleCommand extends Command
{
    protected static $defaultName = 'app:example';
    protected static $defaultDescription = 'Add a short description for your command';

    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        parent::__construct(); // doit être appelé en dernier (obligatoire)
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Ceci est un <info>exemple</info> de message d\'aide')

            /** Mode: REQUIRED, OPTIONAL, IS_ARRAY */
            ->addArgument('name', InputArgument::REQUIRED, "Nom de l'utilisateur")

            /** Mode: VALUE_REQUIRED, VALUE_OPTIONAL, VALUE_IS_ARRAY, VALUE_NONE, VALUE_NEGATABLE */
            ->addOption('characters_case', null, InputOption::VALUE_OPTIONAL, 'Affiche le nom avec la casse choisie <comment>[natural, upper, lower]</comment>', 'natural')
            ->addOption('upper', 'u', InputOption::VALUE_NONE, 'Affiche le nom en majuscule')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if($input->isInteractive()) {
            $io = new SymfonyStyle($input, $output);
            $name = $input->getArgument('name');

            if(!$name) {
                do {
                    $name = $io->ask('Quel est votre nom ?');
                    $name = trim($name);
                    $io->warning('Name does not be empty');
                } while (!$name);

                $input->setArgument('name', $name);
            }
        }
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $case = $input->getOption('characters_case');
        $upper = $input->getOption('upper');

        switch ($case) {
            case 'lower':
                $name = strtolower($name);
                break;
            case 'upper':
                $name = strtoupper($name);
                break;
            case 'natural':
                break;
            default:
                $io->error('characters case value does not exist');
                return Command::INVALID;
        }

        if($upper) {
            $name = strtoupper($name);
        }

        $io->note(sprintf('Hello %s', $name));

        $films = ['Star Wars', 'Avengers', 'Spider Man', 'Thor', 'Iron Man', 'Star Trek'];
        $io->title('Liste des films');
        $io->listing($films);

        $question = new Question('Quel est votre film favori ?');
        $question->setAutocompleterValues($films);
        $reponse = $io->askQuestion($question);

        $io->text("J'adore ".$reponse);

        $cardNumber = $io->askHidden('Entrer votre numéro de carte bancaire !');
        $io->text("Visa: ".$cardNumber);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
