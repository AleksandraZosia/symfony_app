<?php

namespace App\Controller;

use App\Entity\Data;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Repository\DataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

class ExportController extends AbstractController
{
    #[Route('/data-export', name: 'data-export')]
    public function exportUsers(DataRepository $dataRepository): StreamedResponse
    {

        $qb = $dataRepository->createQueryBuilder('d')
                             ->select('d')
                             ->orderBy('d.id', 'ASC');

        $rows = $qb->getQuery()->toIterable();

       
        $excelRows = [];

        foreach ($rows as $entity) {
        $excelRows[] = [
        $entity->getId(),
        $entity->getUser()->getLogin(),
        $entity->getProduct(),
        $entity->getColor(),
        $entity->getAmount(),
        $entity->getDate()?->format('Y-m-d H:i:s'),
        ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Dodano przez', 'Produkt', 'Kolor', 'Liczba produktów', 'Data wpisu'], null, 'A1');
        $sheet->fromArray($excelRows, null, 'A2');

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });
        
        $response->headers->set(
            'Content-Type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="data.xlsx"'
        );
        
        $response->headers->set(
            'Cache-Control',
            'max-age=0'
        );

        return $response;
    }
}