<?php
namespace App\Http\Controllers;

use App\Models\Historia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\HistoriaCreada;
use App\Notifications\HistoriaAsistida;

class HistoriaController extends Controller
{
    public function index()
    {
        $historias = Historia::where('professional_id', Auth::id())
            ->with(['professional', 'patient'])
            ->get();
        return response()->json($historias);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'patient_id' => 'required|integer',
                'patient_info' => 'required|string',
                'date_time' => 'required|date',
                'consecutive' => 'required|string',
                'current_status' => 'nullable|string',
                'antecedents' => 'nullable|string',
                'final_evolution' => 'nullable|string',
                'professional_concept' => 'nullable|string',
                'recommendations' => 'nullable|string',
            ]);
    
            $historia = new Historia();
            $historia->professional_id = Auth::id();
            $historia->patient_id = $request->patient_id;
            $historia->patient_info = $request->patient_info;
            $historia->date_time = $request->date_time;
            $historia->consecutive = $request->consecutive;
            $historia->current_status = $request->current_status;
            $historia->antecedents = $request->antecedents;
            $historia->final_evolution = $request->final_evolution;
            $historia->professional_concept = $request->professional_concept;
            $historia->recommendations = $request->recommendations;
            $historia->save();
    
            // Notificar al paciente
            $patient = User::find($request->patient_id);
            if ($patient) {
                $patient->notify(new HistoriaCreada($historia)); 
            }
    
            return response()->json($historia, 201);
        } catch (\Exception $e) {
            \Log::error('Error al crear la historia: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function show($id)
    {
        $historia = Historia::where('professional_id', Auth::id())->with('patient')->findOrFail($id);
        return response()->json($historia);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'patient_info' => 'required',
            'date_time' => 'required|date',
            'consecutive' => 'required',
        ]);

        $historia = Historia::where('professional_id', Auth::id())->findOrFail($id);
        $historia->update($request->all());

        return response()->json($historia);
    }

    public function destroy($id)
    {
        $historia = Historia::where('professional_id', Auth::id())->findOrFail($id);
        $historia->delete();

        return response()->json(null, 204);
    }

    public function getHistoriasForPatient(Request $request)
    {
        $user = $request->user();

        $historias = Historia::with('professional') 
                            ->where('patient_id', $user->id)
                            ->get();

        return response()->json($historias);
    }

    public function markAsAssisted(Request $request, $id)
    {
        try {
            $request->validate([
                'firma' => 'required|string|max:255',
            ]);

            $historia = Historia::findOrFail($id);
            $historia->assisted = true;
            $historia->firma = $request->firma;
            $historia->save();

            $professional = User::find($historia->professional_id);
            if ($professional) {
                $professional->notify(new HistoriaAsistida($historia, 'professional'));
            }

            return response()->json(['message' => 'Historia marcada como asistida']);
        } catch (\Exception $e) {
            \Log::error('Error al marcar la historia como asistida: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
