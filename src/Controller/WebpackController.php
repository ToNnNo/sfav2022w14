<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * @Route("/webpack", name="webpack_")
 */
class WebpackController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(ChartBuilderInterface $chartBuilder): Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['Janvier', 'Février', 'Mars'],
            'datasets' => [
                [
                    'label' => 'Ensemble de valeur par mois',
                    'data' => [50, 200, 180],
                    'backgroundColor' => '#fff',
                    'borderColor' => '#fff'
                ]
            ]
        ]);
        $chart->setOptions([
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => "Mois",
                        'color' => '#fff'
                    ],
                    'grid' => [
                        'color' => '#fff'
                    ],
                    'ticks' => [
                        'color' => '#fff'
                    ]
                ],
                'y' => [
                    'grid' => [
                        'color' => '#fff'
                    ],
                    'ticks' => [
                        'color' => '#fff'
                    ]
                ]
            ]
        ]);

        return $this->render('webpack/index.html.twig', [
            'chart' => $chart
        ]);
    }
}
