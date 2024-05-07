<?php

namespace App\Controller;

use App\Entity\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcelController extends AbstractController
{
    /**
     * @Route("/excel/users", name="app_excel_users")
     */
    public function userList(): Response
    {
        // Get the UserRepository
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        // Fetch all users
        $users = $userRepository->findAll();

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Prénom');
        $sheet->setCellValue('C1', 'Email');

        // Add user data
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->getNom());
            $sheet->setCellValue('B' . $row, $user->getPrenom());
            $sheet->setCellValue('C' . $row, $user->getEmail());
            $row++;
        }

        // Generate Excel file
        $writer = new Xlsx($spreadsheet);

        // Create a temporary file to store the Excel
        $tempFilePath = tempnam(sys_get_temp_dir(), 'user_list_');
        $writer->save($tempFilePath);

        // Set response headers for Excel download
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="user_list.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->setContent(file_get_contents($tempFilePath));

        // Delete the temporary file
        unlink($tempFilePath);

        return $response;
    }
}
