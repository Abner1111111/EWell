<?php
session_start();
require_once '../includes/db_connection.php';
require_once '../vendor/autoload.php'; // Make sure to install required packages via composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$type = $_GET['type'] ?? '';

if (!in_array($type, ['pdf', 'excel'])) {
    die('Invalid export type');
}

// Fetch financial data
$data = [
    'transactions' => [],
    'budgets' => [],
    'goals' => []
];

// Get transactions
$sql = "SELECT * FROM financial_transactions WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $data['transactions'][] = $row;
}

// Get budgets
$sql = "SELECT * FROM budgets WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $data['budgets'][] = $row;
}

// Get goals
$sql = "SELECT * FROM financial_goals WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $data['goals'][] = $row;
}

if ($type === 'excel') {
    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    
    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator('EWell Financial Planner')
        ->setLastModifiedBy('EWell Financial Planner')
        ->setTitle('Financial Report')
        ->setSubject('Financial Report Export')
        ->setDescription('Financial report exported from EWell Financial Planner');

    // Transactions Sheet
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Transactions');
    
    // Add headers
    $sheet->setCellValue('A1', 'Date');
    $sheet->setCellValue('B1', 'Type');
    $sheet->setCellValue('C1', 'Category');
    $sheet->setCellValue('D1', 'Amount');
    $sheet->setCellValue('E1', 'Description');
    
    // Add data
    $row = 2;
    foreach ($data['transactions'] as $transaction) {
        $sheet->setCellValue('A' . $row, $transaction['date']);
        $sheet->setCellValue('B' . $row, $transaction['type']);
        $sheet->setCellValue('C' . $row, $transaction['category']);
        $sheet->setCellValue('D' . $row, $transaction['amount']);
        $sheet->setCellValue('E' . $row, $transaction['description']);
        $row++;
    }

    // Budgets Sheet
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle('Budgets');
    
    // Add headers
    $sheet->setCellValue('A1', 'Category');
    $sheet->setCellValue('B1', 'Amount');
    $sheet->setCellValue('C1', 'Last Updated');
    
    // Add data
    $row = 2;
    foreach ($data['budgets'] as $budget) {
        $sheet->setCellValue('A' . $row, $budget['category']);
        $sheet->setCellValue('B' . $row, $budget['amount']);
        $sheet->setCellValue('C' . $row, $budget['updated_at']);
        $row++;
    }

    // Goals Sheet
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle('Goals');
    
    // Add headers
    $sheet->setCellValue('A1', 'Name');
    $sheet->setCellValue('B1', 'Target Amount');
    $sheet->setCellValue('C1', 'Current Amount');
    $sheet->setCellValue('D1', 'Deadline');
    $sheet->setCellValue('E1', 'Status');
    
    // Add data
    $row = 2;
    foreach ($data['goals'] as $goal) {
        $sheet->setCellValue('A' . $row, $goal['name']);
        $sheet->setCellValue('B' . $row, $goal['target_amount']);
        $sheet->setCellValue('C' . $row, $goal['current_amount']);
        $sheet->setCellValue('D' . $row, $goal['deadline']);
        $sheet->setCellValue('E' . $row, $goal['status']);
        $row++;
    }

    // Set headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="financial_report.xlsx"');
    header('Cache-Control: max-age=0');

    // Save file to PHP output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} else if ($type === 'pdf') {
    // Create PDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    
    $dompdf = new Dompdf($options);
    
    // Generate HTML content
    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { color: #333; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f5f5f5; }
            .section { margin-bottom: 30px; }
        </style>
    </head>
    <body>
        <h1>Financial Report</h1>
        
        <div class="section">
            <h2>Transactions</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Description</th>
                </tr>';
    
    foreach ($data['transactions'] as $transaction) {
        $html .= '<tr>
            <td>' . htmlspecialchars($transaction['date']) . '</td>
            <td>' . htmlspecialchars($transaction['type']) . '</td>
            <td>' . htmlspecialchars($transaction['category']) . '</td>
            <td>₱' . number_format($transaction['amount'], 2) . '</td>
            <td>' . htmlspecialchars($transaction['description']) . '</td>
        </tr>';
    }
    
    $html .= '</table></div>
        
        <div class="section">
            <h2>Budgets</h2>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Last Updated</th>
                </tr>';
    
    foreach ($data['budgets'] as $budget) {
        $html .= '<tr>
            <td>' . htmlspecialchars($budget['category']) . '</td>
            <td>₱' . number_format($budget['amount'], 2) . '</td>
            <td>' . htmlspecialchars($budget['updated_at']) . '</td>
        </tr>';
    }
    
    $html .= '</table></div>
        
        <div class="section">
            <h2>Financial Goals</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Target Amount</th>
                    <th>Current Amount</th>
                    <th>Deadline</th>
                    <th>Status</th>
                </tr>';
    
    foreach ($data['goals'] as $goal) {
        $html .= '<tr>
            <td>' . htmlspecialchars($goal['name']) . '</td>
            <td>₱' . number_format($goal['target_amount'], 2) . '</td>
            <td>₱' . number_format($goal['current_amount'], 2) . '</td>
            <td>' . htmlspecialchars($goal['deadline']) . '</td>
            <td>' . htmlspecialchars($goal['status']) . '</td>
        </tr>';
    }
    
    $html .= '</table></div>
    </body>
    </html>';
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Output PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="financial_report.pdf"');
    echo $dompdf->output();
    exit;
} 