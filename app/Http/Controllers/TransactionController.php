<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $validated = $this->validateTransaction($request);

        $this->service->createTransaction($validated);

        return redirect()->route('dashboard')->with('success', 'Entry added successfully');
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateTransaction($request);

        $this->service->updateTransaction((int) $id, $validated);

        return redirect()->route('dashboard')->with('success', 'Entry updated successfully');
    }

    public function destroy($id)
    {
        $this->service->deleteTransaction($id);
        return redirect()->route('dashboard')->with('success', 'Entry deleted successfully');
    }

    public function export(Request $request)
    {
        // Reuse the exact same filter logic as the dashboard
        $transactions = $this->service->getFilteredTransactions(
            Auth::id(),
            $request->all()
        );

        $totalSales = $transactions->where('type', 'sale')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        $netProfit = $totalSales - $totalExpenses;

        $brandGreen = '059669';
        $brandGreenDark = '047857';
        $greenLight = 'ECFDF5';
        $greenText = '047857';
        $roseLight = 'FFF1F2';
        $roseText = 'BE123C';
        $roseSolid = 'E11D48';
        $stoneLight = 'FAFAF9';
        $stoneBorder = 'E7E5E4';
        $stoneText = '44403C';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('DukanIQ')
            ->setTitle('Transaction Report')
            ->setSubject('DukanIQ Transaction Export');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transactions');

        $columns = ['A', 'B', 'C', 'D', 'E'];
        $sheet->getColumnDimension('A')->setWidth(14);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(42);
        $sheet->getColumnDimension('E')->setWidth(18);

        // --- Banner ---
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'DukanIQ — Transaction Report');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($brandGreen);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(32);

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', $this->describeExportFilters($request) . '  •  Generated ' . now()->format('d M Y, h:i A'));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($brandGreenDark);
        $sheet->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // --- Table header ---
        $headerRow = 4;
        $headers = ['Date', 'Type', 'Category', 'Description', 'Amount (₹)'];
        foreach ($columns as $i => $col) {
            $sheet->setCellValue("{$col}{$headerRow}", $headers[$i]);
        }
        $sheet->getStyle("A{$headerRow}:E{$headerRow}")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A{$headerRow}:E{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($brandGreen);
        $sheet->getStyle("A{$headerRow}:E{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("E{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

        // --- Data rows ---
        $row = $headerRow + 1;
        foreach ($transactions as $t) {
            $isSale = $t->type === 'sale';

            $sheet->setCellValue("A{$row}", $t->date->format('d M Y'));
            $sheet->setCellValue("B{$row}", $isSale ? 'Income' : 'Expense');
            $sheet->setCellValue("C{$row}", $t->category ?? '-');
            $sheet->setCellValue("D{$row}", $t->description);
            $sheet->setCellValue("E{$row}", ($isSale ? 1 : -1) * $t->amount);

            $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('"₹"#,##0.00;[Red]-"₹"#,##0.00');
            $sheet->getStyle("B{$row}")->getFont()->setBold(true)->getColor()->setRGB($isSale ? $greenText : $roseText);
            $sheet->getStyle("E{$row}")->getFont()->setBold(true)->getColor()->setRGB($isSale ? $greenText : $roseText);
            $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:E{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($stoneLight);
            }

            $row++;
        }
        $lastDataRow = $row - 1;

        if ($lastDataRow >= $headerRow + 1) {
            $sheet->getStyle("A{$headerRow}:E{$lastDataRow}")
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->getColor()->setRGB($stoneBorder);
        }

        $sheet->freezePane("A" . ($headerRow + 1));

        // --- Summary block ---
        $summaryRow = $lastDataRow + 2;

        $this->writeExportSummaryRow(
            $sheet,
            $summaryRow,
            'Total Sales (Income)',
            $totalSales,
            $greenLight,
            $greenText,
            false
        );

        $this->writeExportSummaryRow(
            $sheet,
            $summaryRow + 1,
            'Total Expenses',
            -$totalExpenses,
            $roseLight,
            $roseText,
            false
        );

        $this->writeExportSummaryRow(
            $sheet,
            $summaryRow + 2,
            'NET PROFIT',
            $netProfit,
            $netProfit >= 0 ? $brandGreen : $roseSolid,
            'FFFFFF',
            true
        );

        $sheet->getStyle("A1:E" . ($summaryRow + 2))->getFont()->setName('Calibri');

        $writer = new Xlsx($spreadsheet);
        $filename = 'dukaniq-transactions-' . now()->format('Y-m-d-His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ]);
    }

    private function writeExportSummaryRow($sheet, int $row, string $label, float $amount, string $fillRgb, string $textRgb, bool $emphasize): void
    {
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->setCellValue("A{$row}", $label);
        $sheet->setCellValue("E{$row}", $amount);
        $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('"₹"#,##0.00;-"₹"#,##0.00');

        $sheet->getStyle("A{$row}:E{$row}")->getFont()
            ->setBold(true)
            ->setSize($emphasize ? 13 : 11)
            ->getColor()->setRGB($textRgb);
        $sheet->getStyle("A{$row}:E{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($fillRgb);
        $sheet->getStyle("A{$row}:E{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($row)->setRowHeight($emphasize ? 26 : 22);
    }

    private function describeExportFilters(Request $request): string
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = \Carbon\Carbon::parse($request->input('start_date'))->format('d M Y');
            $end = \Carbon\Carbon::parse($request->input('end_date'))->format('d M Y');
            $period = "{$start} - {$end}";
        } else {
            $period = match ($request->input('date', 'month')) {
                'today' => 'Today',
                'all' => 'All Time',
                default => 'This Month',
            };
        }

        $type = $request->input('type', 'all');
        if ($type === 'sale') {
            $period .= ' • Income Only';
        } elseif ($type === 'expense') {
            $period .= ' • Expenses Only';
        }

        $category = $request->input('category');
        if ($category && $category !== 'all') {
            $period .= " • {$category}";
        }

        return $period;
    }

    private function validateTransaction(Request $request): array
    {
        return $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:sale,expense',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category' => 'nullable|string|max:50|required_if:type,expense',
        ]);
    }
}
