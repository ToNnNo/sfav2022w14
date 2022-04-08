<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class MakeActionCommand extends Command
{
    protected static $defaultName = 'app:make:action';
    protected static $defaultDescription = 'Create new action in Controller';

    private $filesystem;
    private $parameterBag;

    private $directory;

    public function __construct(Filesystem $filesystem, ParameterBagInterface $parameterBag) // appeler les services ici
    {
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('controller', InputArgument::REQUIRED, 'Controller name')
            ->addArgument('action', InputArgument::REQUIRED, 'Action name')
            ->addOption('skipView', 's', InputOption::VALUE_NONE, 'Ne crée pas le fichier de vue')
            ->addOption('filename', 'f', InputOption::VALUE_REQUIRED, 'Change le nom du fichier de vue')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $controller = $input->getArgument('controller');

        if(null !== $controller) {
            $controllerList = $this->getControllerList();

            if (!in_array($controller, (array)$controllerList)) {
                throw new \RuntimeException('Controller do not exists');
            }
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $helper = new SymfonyStyle($input, $output);
        $controllerList = $this->getControllerList();

        $helper->title("Create new Action");

        $controller = $input->getArgument('controller');
        if (!$controller) {
            $helper->section("Controller List");
            $helper->listing($controllerList);

            $question = new Question('Enter the controller name');
            $question->setAutocompleterValues($controllerList);
            $question->setValidator(function ($answer) use ($question) {
                if ($answer === null) {
                    throw new \RuntimeException('Controller name cannot be null');
                }

                if (!in_array($answer, (array)$question->getAutocompleterValues())) {
                    throw new \RuntimeException('Controller do not exists');
                }

                return $answer;
            });
            $question->setMaxAttempts(1);

            $controller = $helper->askQuestion($question);
            $input->setArgument('controller', $controller);
        }

        $action = $input->getArgument('action');
        if (!$action) {
            $question = new Question('Enter the action name');
            $question->setValidator(function ($answer) use ($controller, $helper) {
                if ($answer === null) {
                    throw new \RuntimeException('Action name cannot be null');
                }

                /*if (method_exists($controller, $answer)) {
                    throw new \RuntimeException('This action already exists');
                }*/

                return $answer;
            });
            $question->setMaxAttempts(1);
            $action = $helper->askQuestion($question);
            $input->setArgument('action', $action);
        }

        // controler $controller et $action si valeur existe
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $controller = $input->getArgument('controller');
        $action = $input->getArgument('action');
        $skipView = $input->getOption('skipView');
        $filename = $input->getOption('filename') ?? $action;

        $controllerContent = $this->getContentOfController($controller);
        $actionTpl = $this->getTemplateAction($controller, $filename);

        $controllerContent = substr(rtrim($controllerContent), 0, -1) . "\n" . $actionTpl . "\n}\n";

        $this->filesystem->dumpFile(dirname(__DIR__, 1) . "/Controller/" . $controller . ".php", $controllerContent);

        if(!$skipView) {
            $this->createViewFile($filename);
        }

        $io->success('New action (' . $action . ') add in Controller ' . $controller);

        return Command::SUCCESS; // 0 SUCCESS; 1+ ERROR
    }

    private function getControllerList(): array
    {
        $finder = new Finder();
        $files = $finder->in(dirname(__DIR__, 1) . "/Controller")
            ->name('*.php')
            ->sortByName()
            ->files();

        $autocomplete = [];

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $autocomplete[] = $file->getFilenameWithoutExtension();
        }

        return $autocomplete;
    }

    private function getContentOfController($controller)
    {
        $finder = new Finder();
        $files = iterator_to_array($finder->in(dirname(__DIR__, 1) . "/Controller")
            ->name($controller . '.php'));

        /** @var \SplFileInfo $file */
        $file = array_shift($files);

        return $file->getContents();
    }

    private function getTemplateAction($controller, $action)
    {
        $converter = new CamelCaseToSnakeCaseNameConverter(); // Serializer/Normalizer

        $this->directory = $converter->normalize(str_replace('Controller', '', $controller));
        $route_name = $this->directory . "_" . $action;

        $content = <<<EOF
    /**
     * @Route("/$action", name="$route_name")
     */
    public function $action()
    {
        return \$this->render('$this->directory/$action.html.twig', []);
    }
EOF;

        return $content;
    }

    private function createViewFile($filename) {
        $path = $this->parameterBag->get('kernel.project_dir')."/templates/".$this->directory."/".$filename.".html.twig";
        $content = <<<EOF
{% extends 'base.html.twig' %}

{% block title %}$filename{% endblock %}

{% block body %}

{% endblock %}
EOF;

        $this->filesystem->appendToFile($path, $content);
    }
}
