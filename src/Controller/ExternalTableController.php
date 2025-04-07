<?php

namespace App\Controller;

use App\Entity\Transaction;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ExternalTableController extends AbstractController
{



    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {}


    #[Route('/upload-table', name: 'upload_table', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Tu ne peut pas acces a cet ressource')]
    public function uploadTable(Request $request, EntityManagerInterface $entityManager): Response
    {

        if ($request->isMethod('POST')) {
            $file = $request->files->get('csv_file');
            if ($file && $file->isValid()) {
                $extension = $file->getClientOriginalExtension();
                $data = [];

                if ($extension === 'csv') {
                    $data = $this->parseCsvFile($file->getPathname());
                } elseif (in_array($extension, ['xls', 'xlsx'])) {
                    $data = $this->parseExcelFile($file->getPathname());
                }

                // Enregistrer chaque ligne dans la base de données
                foreach ($data as $row) {
                    if (count($row) >= 7) {
                        $transaction = new Transaction();
                        $transaction->setMotif($row[3]);
                        $transaction->setType($row[4]);
                        $transaction->setMoyen($row[5]);
                        $transaction->setCommande($row[6]);

                        $date = \DateTime::createFromFormat('d/m/Y', $row[7]);
                        if ($date === false) {
                            // Gérer les erreurs de date si le format est incorrect 
                            continue;
                        }
                        $transaction->setDatePaiement($date);

                        // $transaction->setDatePaiement(new \DateTime($row[3]));
                        $row[8] = str_replace(',', '.', $row[8]);
                        $debitValue = is_numeric($row[8]) ? floatval($row[8]) : null;
                        $transaction->setDebit($debitValue);

                        $row[9] = str_replace(',', '.', $row[9]);
                        $CreditValue = is_numeric($row[9]) ? floatval($row[9]) : null;
                        $transaction->setCredit($CreditValue);

                        $entityManager->persist($transaction);
                    } else {
                        return $this->redirectToRoute('app_transaction_index');
                    }
                }

                $entityManager->flush();

                return $this->redirectToRoute('app_transaction_index'); // Redirigez vers une page où les transactions sont listées
            }
        }

        return $this->render('transaction/upload.html.twig');
    }

    private function parseCsvFile(string $filePath): array
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }
        return $rows;
    }
    private function parseExcelFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        return $worksheet->toArray();
    }
}
