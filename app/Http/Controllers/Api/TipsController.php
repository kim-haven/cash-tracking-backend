<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipRequest;
use App\Models\Tip;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TipsController extends Controller
{
    /**
     * Spreadsheet / import column titles (match tip export files).
     *
     * @var list<string>
     */
    public const TIP_FILE_HEADERS = [
        'Initials',
        'Cash Tip Amount',
        'End of Pay Period Total',
        'Cash Balance',
        'Date',
        'Cash tip',
        'Credit tips',
        'Ach tips',
        'Debit tips',
        'Total',
        'NOTE',
    ];

    public function index()
    {
        $tips = Tip::with('expense')->latest('date')->latest('id')->get();

        return response()->json([
            'data' => $tips,
        ]);
    }

    public function store(StoreTipRequest $request)
    {
        $tip = Tip::create($request->validated());

        return response()->json([
            'message' => 'Tip record created successfully',
            'data' => $tip->load('expense'),
        ], 201);
    }

    public function show($id)
    {
        $tip = Tip::with('expense')->findOrFail($id);

        return response()->json([
            'data' => $tip,
        ]);
    }

    public function update(StoreTipRequest $request, $id)
    {
        $tip = Tip::findOrFail($id);
        $tip->update($request->validated());

        return response()->json([
            'message' => 'Tip record updated successfully',
            'data' => $tip->load('expense'),
        ]);
    }

    public function destroy($id)
    {
        $tip = Tip::findOrFail($id);
        $tip->delete();

        return response()->json([
            'message' => 'Tip record deleted successfully',
        ]);
    }

    /**
     * CSV with header row only (for tip file imports aligned with the API).
     */
    public function downloadTemplate(): StreamedResponse
    {
        $headers = self::TIP_FILE_HEADERS;

        return response()->streamDownload(static function () use ($headers): void {
            $out = fopen('php://output', 'w');
            if ($out !== false) {
                fputcsv($out, $headers);
                fclose($out);
            }
        }, 'tips_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

}
