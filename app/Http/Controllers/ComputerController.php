<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Models\ComputerReport;
use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public function index()
    {
        $computers = Computer::with('reports')->orderBy('pc_number')->get();
        return view('computers.index', compact('computers'));
    }

    public function create()
    {
        return view('computers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pc_number' => 'required|string|max:50|unique:computers',
            'location' => 'required|string|max:255',
            'status' => 'required|in:functional,damaged,repairing,retired',
        ]);

        $computerData = [
            'pc_number' => $validated['pc_number'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'brand' => $request->brand,
            'model' => $request->model,
            'processor' => $request->processor,
            'ram' => $request->ram,
            'storage' => $request->storage,
            'operating_system' => $request->operating_system,
            'monitor' => $request->monitor,
            'keyboard' => $request->keyboard,
            'mouse' => $request->mouse,
            'notes' => $request->notes,
            'purchase_date' => $request->purchase_date,
        ];

        Computer::create($computerData);
        return redirect()->route('computers.index')->with('success', 'Equipo registrado exitosamente');
    }

    public function show(Computer $computer)
    {
        $computer->load('reports');
        return view('computers.show', compact('computer'));
    }

    public function edit(Computer $computer)
    {
        return view('computers.edit', compact('computer'));
    }

    public function update(Request $request, Computer $computer)
    {
        $validated = $request->validate([
            'pc_number' => 'required|string|max:50|unique:computers,pc_number,' . $computer->id,
            'location' => 'required|string|max:255',
            'status' => 'required|in:functional,damaged,repairing,retired',
        ]);

        $computer->update([
            'pc_number' => $validated['pc_number'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'brand' => $request->brand,
            'model' => $request->model,
            'processor' => $request->processor,
            'ram' => $request->ram,
            'storage' => $request->storage,
            'operating_system' => $request->operating_system,
            'monitor' => $request->monitor,
            'keyboard' => $request->keyboard,
            'mouse' => $request->mouse,
            'notes' => $request->notes,
            'purchase_date' => $request->purchase_date,
        ]);
        
        return redirect()->route('computers.index')->with('success', 'Equipo actualizado exitosamente');
    }

    public function destroy(Computer $computer)
    {
        $computer->delete();
        return redirect()->route('computers.index')->with('success', 'Equipo eliminado exitosamente');
    }

    public function reportIndex()
    {
        $reports = ComputerReport::with('computer')->orderBy('report_date', 'desc')->get();
        return view('computers.reports', compact('reports'));
    }

    public function reportCreate()
    {
        $computers = Computer::whereIn('status', ['functional', 'damaged'])->orderBy('pc_number')->get();
        return view('computers.report-create', compact('computers'));
    }

    public function reportStore(Request $request)
    {
        $validated = $request->validate([
            'computer_id' => 'required|exists:computers,id',
            'description' => 'required|string',
            'reported_by' => 'required|string|max:255',
            'report_date' => 'required|date',
        ]);

        ComputerReport::create($validated);

        $computer = Computer::find($validated['computer_id']);
        $computer->update(['status' => 'damaged']);

        return redirect()->route('computers.reports')->with('success', 'Reporte creado exitosamente');
    }

    public function reportResolve(ComputerReport $report)
    {
        $report->update([
            'status' => 'resolved',
            'resolved_date' => now()->toDateString(),
        ]);

        $report->computer->update(['status' => 'functional']);

        return back()->with('success', 'Reporte marcado como resuelto');
    }
}
