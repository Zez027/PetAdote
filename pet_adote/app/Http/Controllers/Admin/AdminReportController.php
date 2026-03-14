<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'reportable'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendente,em_analise,resolvida,descartada'
        ]);

        $report->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Estado da denúncia atualizado com sucesso.');
    }
}