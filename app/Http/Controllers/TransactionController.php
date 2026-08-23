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

        $sales = $transactions->where('type', 'sale')->values();
        $expenses = $transactions->where('type', 'expense')->values();

        $totalSales = (float) $sales->sum('amount');
        $totalExpenses = (float) $expenses->sum('amount');
        $netProfit = $totalSales - $totalExpenses;

        $palette = [
            'green' => '059669',
            'greenDark' => '047857',
            'greenLight' => 'ECFDF5',
            'greenText' => '047857',
            'rose' => 'E11D48',
            'roseDark' => 'BE123C',
            'roseLight' => 'FFF1F2',
            'roseText' => 'BE123C',
            'stoneLight' => 'FAFAF9',
            'stoneBorder' => 'E7E5E4',
            'stoneText' => '78716C',
        ];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('DukanIQ')
            ->setTitle('Transaction Report')
            ->setSubject('DukanIQ Transaction Export');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transactions');

        // Sales side: A-C. Gap: D. Expenses side: E-H.
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(3);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(14);
        $sheet->getColumnDimension('G')->setWidth(26);
        $sheet->getColumnDimension('H')->setWidth(15);

        // --- Banner ---
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'DukanIQ — Transaction Report');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['green']);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(32);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', $this->describeExportFilters($request) . '  •  Generated ' . now()->format('d M Y, h:i A'));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['greenDark']);
        $sheet->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(20);

        $sectionRow = 4;

        $salesLastRow = $this->writeSalesSide($sheet, $sectionRow, $sales, $palette);
        $expensesLastRow = $this->writeExpensesSide($sheet, $sectionRow, $expenses, $palette);

        $sheet->freezePane('A' . ($sectionRow + 2));

        // --- Final calculation, full width: Total Sales − Total Expenses = Net Profit/Loss ---
        $finalRow = max($salesLastRow, $expensesLastRow) + 2;

        $sheet->mergeCells("A{$finalRow}:E{$finalRow}");
        $sheet->setCellValue(
            "A{$finalRow}",
            sprintf(
                'NET PROFIT / LOSS   =   Total Sales ₹%s   −   Total Expenses ₹%s',
                number_format($totalSales, 2),
                number_format($totalExpenses, 2)
            )
        );
        $sheet->mergeCells("F{$finalRow}:H{$finalRow}");
        $sheet->setCellValue("F{$finalRow}", $netProfit);
        $sheet->getStyle("F{$finalRow}")->getNumberFormat()->setFormatCode('"₹"#,##0.00;-"₹"#,##0.00');
        $sheet->getStyle("F{$finalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $netFill = $netProfit >= 0 ? $palette['green'] : $palette['rose'];
        $sheet->getStyle("A{$finalRow}:H{$finalRow}")->getFont()->setBold(true)->setSize(13)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A{$finalRow}:H{$finalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($netFill);
        $sheet->getStyle("A{$finalRow}:H{$finalRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($finalRow)->setRowHeight(28);

        $sheet->getStyle("A1:H{$finalRow}")->getFont()->setName('Calibri');

        $writer = new Xlsx($spreadsheet);
        $filename = 'dukaniq-transactions-' . now()->format('Y-m-d-His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ]);
    }

    /**
     * Writes the Sales/Income ledger side (columns A-C) starting at $sectionRow.
     * Returns the last row used by this side (its own "Total Sales" row).
     */
    private function writeSalesSide($sheet, int $sectionRow, $sales, array $palette): int
    {
        $sheet->mergeCells("A{$sectionRow}:C{$sectionRow}");
        $sheet->setCellValue("A{$sectionRow}", 'SALES / INCOME');
        $sheet->getStyle("A{$sectionRow}:C{$sectionRow}")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A{$sectionRow}:C{$sectionRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['green']);
        $sheet->getStyle("A{$sectionRow}:C{$sectionRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($sectionRow)->setRowHeight(20);

        $headerRow = $sectionRow + 1;
        $sheet->setCellValue("A{$headerRow}", 'Date');
        $sheet->setCellValue("B{$headerRow}", 'Description');
        $sheet->setCellValue("C{$headerRow}", 'Amount (₹)');
        $sheet->getStyle("A{$headerRow}:C{$headerRow}")->getFont()->setBold(true)->getColor()->setRGB($palette['greenText']);
        $sheet->getStyle("A{$headerRow}:C{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['greenLight']);
        $sheet->getStyle("C{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $row = $headerRow + 1;

        if ($sales->isEmpty()) {
            $sheet->mergeCells("A{$row}:C{$row}");
            $sheet->setCellValue("A{$row}", 'No income entries for this period.');
            $sheet->getStyle("A{$row}")->getFont()->setItalic(true)->getColor()->setRGB($palette['stoneText']);
            $row++;
        } else {
            foreach ($sales as $t) {
                $sheet->setCellValue("A{$row}", $t->date->format('d M Y'));
                $sheet->setCellValue("B{$row}", $t->description);
                $sheet->setCellValue("C{$row}", (float) $t->amount);
                $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('"₹"#,##0.00');
                $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("A{$row}:C{$row}")->getFont()->getColor()->setRGB($palette['greenText']);

                if ($row % 2 === 0) {
                    $sheet->getStyle("A{$row}:C{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['stoneLight']);
                }

                $row++;
            }
        }

        $lastDataRow = $row - 1;
        $sheet->getStyle("A{$headerRow}:C{$lastDataRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setRGB($palette['stoneBorder']);

        $totalRow = $row;
        $sheet->mergeCells("A{$totalRow}:B{$totalRow}");
        $sheet->setCellValue("A{$totalRow}", 'Total Sales');
        $sheet->setCellValue("C{$totalRow}", (float) $sales->sum('amount'));
        $sheet->getStyle("C{$totalRow}")->getNumberFormat()->setFormatCode('"₹"#,##0.00');
        $sheet->getStyle("A{$totalRow}:C{$totalRow}")->getFont()->setBold(true)->getColor()->setRGB($palette['greenText']);
        $sheet->getStyle("A{$totalRow}:C{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['greenLight']);
        $sheet->getStyle("C{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($totalRow)->setRowHeight(22);

        return $totalRow;
    }

    /**
     * Writes the Expenses ledger side (columns E-H) starting at $sectionRow,
     * grouped by category with a subtotal under each group.
     * Returns the last row used by this side (its own "Total Expenses" row).
     */
    private function writeExpensesSide($sheet, int $sectionRow, $expenses, array $palette): int
    {
        $sheet->mergeCells("E{$sectionRow}:H{$sectionRow}");
        $sheet->setCellValue("E{$sectionRow}", 'EXPENSES');
        $sheet->getStyle("E{$sectionRow}:H{$sectionRow}")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("E{$sectionRow}:H{$sectionRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['rose']);
        $sheet->getStyle("E{$sectionRow}:H{$sectionRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $headerRow = $sectionRow + 1;
        $sheet->setCellValue("E{$headerRow}", 'Date');
        $sheet->setCellValue("F{$headerRow}", 'Category');
        $sheet->setCellValue("G{$headerRow}", 'Description');
        $sheet->setCellValue("H{$headerRow}", 'Amount (₹)');
        $sheet->getStyle("E{$headerRow}:H{$headerRow}")->getFont()->setBold(true)->getColor()->setRGB($palette['roseText']);
        $sheet->getStyle("E{$headerRow}:H{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['roseLight']);
        $sheet->getStyle("H{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $row = $headerRow + 1;

        if ($expenses->isEmpty()) {
            $sheet->mergeCells("E{$row}:H{$row}");
            $sheet->setCellValue("E{$row}", 'No expense entries for this period.');
            $sheet->getStyle("E{$row}")->getFont()->setItalic(true)->getColor()->setRGB($palette['stoneText']);
            $row++;
        } else {
            $groups = $expenses->groupBy(fn ($t) => $t->category ?: 'Other')->sortKeys();

            foreach ($groups as $category => $items) {
                foreach ($items as $t) {
                    $sheet->setCellValue("E{$row}", $t->date->format('d M Y'));
                    $sheet->setCellValue("F{$row}", $t->category ?? '-');
                    $sheet->setCellValue("G{$row}", $t->description);
                    $sheet->setCellValue("H{$row}", (float) $t->amount);
                    $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('"₹"#,##0.00');
                    $sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("E{$row}:H{$row}")->getFont()->getColor()->setRGB($palette['roseText']);

                    if ($row % 2 === 0) {
                        $sheet->getStyle("E{$row}:H{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['stoneLight']);
                    }

                    $row++;
                }

                // Category subtotal, shown whenever more than one category is present.
                if ($groups->count() > 1) {
                    $sheet->mergeCells("E{$row}:G{$row}");
                    $sheet->setCellValue("E{$row}", "Subtotal — {$category}");
                    $sheet->setCellValue("H{$row}", (float) $items->sum('amount'));
                    $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('"₹"#,##0.00');
                    $sheet->getStyle("E{$row}:H{$row}")->getFont()->setItalic(true)->setBold(true)->setSize(9)->getColor()->setRGB($palette['stoneText']);
                    $sheet->getStyle("E{$row}:H{$row}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB($palette['stoneBorder']);
                    $sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $row++;
                }
            }
        }

        $lastDataRow = $row - 1;
        $sheet->getStyle("E{$headerRow}:H{$lastDataRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setRGB($palette['stoneBorder']);

        $totalRow = $row;
        $sheet->mergeCells("E{$totalRow}:G{$totalRow}");
        $sheet->setCellValue("E{$totalRow}", 'Total Expenses');
        $sheet->setCellValue("H{$totalRow}", (float) $expenses->sum('amount'));
        $sheet->getStyle("H{$totalRow}")->getNumberFormat()->setFormatCode('"₹"#,##0.00');
        $sheet->getStyle("E{$totalRow}:H{$totalRow}")->getFont()->setBold(true)->getColor()->setRGB($palette['roseText']);
        $sheet->getStyle("E{$totalRow}:H{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($palette['roseLight']);
        $sheet->getStyle("H{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($totalRow)->setRowHeight(22);

        return $totalRow;
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
