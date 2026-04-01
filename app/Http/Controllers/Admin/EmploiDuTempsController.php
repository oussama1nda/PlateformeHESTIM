<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmploiDuTemps;
use App\Models\Cour;
use App\Models\Salle;
use App\Models\Groupe;

class EmploiDuTempsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emploisDuTemps = EmploiDuTemps::with(['cours', 'salle', 'groupe', 'cours.enseignant'])
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get();
        
        return view('admin.emploi-du-temps.index', compact('emploisDuTemps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cours = Cour::with('enseignant')->get();
        $salles = Salle::where('disponible', true)->get();
        $groupes = Groupe::with('departement')->get();
        
        return view('admin.emploi-du-temps.create', compact('cours', 'salles', 'groupes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'cours_id' => 'required|exists:cours,id',
                'salle_id' => 'required|exists:salles,id',
                'groupe_id' => 'required|exists:groupes,id',
                'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i|after:heure_debut',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date|after_or_equal:date_debut',
                'type_seance' => 'required|in:cours,td,tp',
            ], [
                'cours_id.required' => 'Le cours est obligatoire.',
                'salle_id.required' => 'La salle est obligatoire.',
                'groupe_id.required' => 'Le groupe est obligatoire.',
                'jour.required' => 'Le jour est obligatoire.',
                'heure_debut.required' => 'L\'heure de début est obligatoire.',
                'heure_fin.required' => 'L\'heure de fin est obligatoire.',
                'heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début.',
                'date_debut.required' => 'La date de début est obligatoire.',
                'date_fin.required' => 'La date de fin est obligatoire.',
                'date_fin.after_or_equal' => 'La date de fin doit être après ou égale à la date de début.',
                'type_seance.required' => 'Le type de séance est obligatoire.',
            ]);

            // Vérifier les conflits
            $hasConflict = EmploiDuTemps::hasConflict(
                $validated['salle_id'],
                $validated['jour'],
                $validated['heure_debut'],
                $validated['heure_fin']
            );

            if ($hasConflict) {
                return redirect()->back()
                    ->with('error', 'Conflit détecté : La salle est déjà réservée à cette date et heure pour un autre cours.')
                    ->withInput();
            }

            EmploiDuTemps::create($validated);

            return redirect()->route('admin.emploi-du-temps.index')
                ->with('success', 'Emploi du temps créé avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmploiDuTemps $emploiDuTemps)
    {
        $cours = Cour::with('enseignant')->get();
        $salles = Salle::where('disponible', true)->get();
        $groupes = Groupe::with('departement')->get();
        
        return view('admin.emploi-du-temps.edit', compact('emploiDuTemps', 'cours', 'salles', 'groupes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmploiDuTemps $emploiDuTemps)
    {
        try {
            $validated = $request->validate([
                'cours_id' => 'required|exists:cours,id',
                'salle_id' => 'required|exists:salles,id',
                'groupe_id' => 'required|exists:groupes,id',
                'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i|after:heure_debut',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date|after_or_equal:date_debut',
                'type_seance' => 'required|in:cours,td,tp',
            ], [
                'cours_id.required' => 'Le cours est obligatoire.',
                'salle_id.required' => 'La salle est obligatoire.',
                'groupe_id.required' => 'Le groupe est obligatoire.',
                'jour.required' => 'Le jour est obligatoire.',
                'heure_debut.required' => 'L\'heure de début est obligatoire.',
                'heure_fin.required' => 'L\'heure de fin est obligatoire.',
                'heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début.',
                'date_debut.required' => 'La date de début est obligatoire.',
                'date_fin.required' => 'La date de fin est obligatoire.',
                'date_fin.after_or_equal' => 'La date de fin doit être après ou égale à la date de début.',
                'type_seance.required' => 'Le type de séance est obligatoire.',
            ]);

            // Vérifier les conflits (exclure l'emploi du temps actuel)
            $hasConflict = EmploiDuTemps::hasConflict(
                $validated['salle_id'],
                $validated['jour'],
                $validated['heure_debut'],
                $validated['heure_fin'],
                $emploiDuTemps->id
            );

            if ($hasConflict) {
                return redirect()->back()
                    ->with('error', 'Conflit détecté : La salle est déjà réservée à cette date et heure pour un autre cours.')
                    ->withInput();
            }

            $emploiDuTemps->update($validated);

            return redirect()->route('admin.emploi-du-temps.index')
                ->with('success', 'Emploi du temps mis à jour avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmploiDuTemps $emploiDuTemps)
    {
        try {
            $emploiDuTemps->delete();
            
            return redirect()->route('admin.emploi-du-temps.index')
                ->with('success', 'Emploi du temps supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.emploi-du-temps.index')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Vérifier les conflits d'emploi du temps
     */
    public function checkConflict(Request $request)
    {
        $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i',
        ]);

        $hasConflict = EmploiDuTemps::hasConflict(
            $request->salle_id,
            $request->jour,
            $request->heure_debut,
            $request->heure_fin
        );

        return response()->json([
            'has_conflict' => $hasConflict
        ]);
    }
}
