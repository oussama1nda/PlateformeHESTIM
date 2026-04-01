<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmploiDuTemps;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AnalyseDonneesController extends Controller
{
    /**
     * Analyse des données (taux d'occupation) depuis l'emploi du temps réel.
     */
    public function index(Request $request)
    {
        $salleId = $request->integer('salle_id');
        $typeSeance = $request->input('type_seance'); // null|cours|td|tp
        $dateFrom = $request->input('date_from'); // YYYY-MM-DD
        $dateTo = $request->input('date_to');     // YYYY-MM-DD

        $salles = Salle::orderBy('nom')->get(['id', 'nom', 'capacite', 'type', 'disponible']);

        // 1) Charger les lignes pertinentes depuis la BDD
        $query = EmploiDuTemps::query()
            ->select([
                'salle_id',
                'jour',
                'heure_debut',
                'heure_fin',
                'type_seance',
                'date_debut',
                'date_fin',
            ]);

        if (!empty($salleId)) {
            $query->where('salle_id', $salleId);
        }

        if (!empty($typeSeance)) {
            $query->where('type_seance', $typeSeance);
        }

        // Filtrage période : recouvrement avec [dateFrom, dateTo]
        if (!empty($dateFrom) && !empty($dateTo)) {
            $query->where(function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('date_debut', [$dateFrom, $dateTo])
                    ->orWhereBetween('date_fin', [$dateFrom, $dateTo])
                    ->orWhere(function ($q2) use ($dateFrom, $dateTo) {
                        $q2->whereNotNull('date_debut')
                            ->whereNotNull('date_fin')
                            ->where('date_debut', '<=', $dateTo)
                            ->where('date_fin', '>=', $dateFrom);
                    });
            });
        }

        $rows = $query->get();

        // 2) Cas vide
        if ($rows->isEmpty()) {
            return view('admin.analyse_donnees.index', [
                'salles' => $salles,
                'salleId' => $salleId,
                'typeSeance' => $typeSeance,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'days' => ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
                'hoursLabels' => [],
                'heatmapZ' => [],
                'roomBar' => ['labels' => [], 'rates' => []],
                'dayBar' => ['labels' => [], 'rates' => []],
                'typeBar' => ['labels' => [], 'rates' => []],
                'summary' => [
                    'roomsCount' => 0,
                    'scheduledHours' => 0,
                    'avgOccupationPct' => 0,
                    'entriesCount' => 0,
                ],
            ]);
        }

        // 3) Paramètres axes (jours/heures)
        $dayOrder = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
        $daysInData = $rows->pluck('jour')->unique()->all();
        $days = array_values(array_filter($dayOrder, fn ($d) => in_array($d, $daysInData, true)));
        if (count($days) === 0) {
            $days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        }

        $timeBounds = $this->computeTimeBounds($rows);
        $hoursLabels = $timeBounds['hoursLabels'];
        $hours = $timeBounds['hours']; // liste d'heures (int) représentant des slots [h:00..(h+1):00]

        if (empty($hours)) {
            // fallback
            $hours = range(8, 17);
            $hoursLabels = array_map(fn ($h) => sprintf('%02d:00', $h), $hours);
        }

        $selectedSalleIds = $this->resolveSelectedRooms($rows, $salleId);
        $roomsCount = count($selectedSalleIds);
        if ($roomsCount === 0) {
            $roomsCount = 1; // sécurité
        }

        // 4) Calcul occupation : minutes par cellule (jour x heure) et par salle
        $calc = $this->calculateOccupation($rows, $days, $hours, $selectedSalleIds);

        // 5) Heatmap + histogrammes (taux d'occupation)
        $heatmapZ = $calc['heatmapZ']; // matrice [jours][heures]
        $roomBar = $calc['roomBar'];  // top salles
        $dayBar = $calc['dayBar'];    // occupation moyenne par jour
        $typeBar = $calc['typeBar'];  // occupation moyenne par type

        // 6) Résumé (cards)
        $summary = [
            'roomsCount' => $roomsCount,
            'scheduledHours' => round($calc['totalMinutes'] / 60, 2),
            'avgOccupationPct' => round($calc['avgOccupationPct'], 2),
            'entriesCount' => $rows->count(),
        ];

        return view('admin.analyse_donnees.index', [
            'salles' => $salles,
            'salleId' => $salleId,
            'typeSeance' => $typeSeance,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'days' => $days,
            'hoursLabels' => $hoursLabels,
            'heatmapZ' => $heatmapZ,
            'roomBar' => $roomBar,
            'dayBar' => $dayBar,
            'typeBar' => $typeBar,
            'summary' => $summary,
        ]);
    }

    private function resolveSelectedRooms(Collection $rows, ?int $salleId): array
    {
        if (!empty($salleId)) {
            return [$salleId];
        }

        return $rows->pluck('salle_id')->unique()->values()->all();
    }

    /**
     * Determine les bornes d'horaires à afficher (slots horaires d'1 heure).
     *
     * @return array{hoursLabels: array<int,string>, hours: array<int,int>}
     */
    private function computeTimeBounds(Collection $rows): array
    {
        $minHour = PHP_INT_MAX;
        $maxHourExclusive = 0;

        foreach ($rows as $r) {
            $startMin = $this->timeToMinutes($r->heure_debut);
            $endMin = $this->timeToMinutes($r->heure_fin);
            if ($endMin <= $startMin) {
                continue;
            }

            $minHour = min($minHour, intdiv($startMin, 60));
            // ceil(endMin/60) => exclusive upper bound
            $maxHourExclusive = max($maxHourExclusive, intdiv($endMin + 59, 60));
        }

        if ($minHour === PHP_INT_MAX || $maxHourExclusive <= $minHour) {
            return ['hoursLabels' => [], 'hours' => []];
        }

        $slotCount = $maxHourExclusive - $minHour;
        $maxSlotsAllowed = 14;
        if ($slotCount > $maxSlotsAllowed) {
            $maxHourExclusive = $minHour + $maxSlotsAllowed;
        }

        $hours = range($minHour, $maxHourExclusive - 1);
        $hoursLabels = array_map(fn ($h) => sprintf('%02d:00', $h), $hours);

        return ['hoursLabels' => $hoursLabels, 'hours' => $hours];
    }

    private function timeToMinutes($time): int
    {
        if (empty($time)) {
            return 0;
        }

        $s = trim((string) $time);
        // HH:MM:SS ou HH:MM
        if (preg_match('/^(\\d{1,2}):(\\d{2})(?::(\\d{2}))?$/', $s, $m) !== 1) {
            return 0;
        }

        $h = (int) $m[1];
        $mm = (int) $m[2];
        // Ignore secondes
        return $h * 60 + $mm;
    }

    private function calculateOccupation(Collection $rows, array $days, array $hours, array $selectedSalleIds): array
    {
        $roomsCount = max(1, count($selectedSalleIds));
        $rangeStart = min($hours) * 60;
        $rangeEndExclusive = (max($hours) + 1) * 60;

        // Minutes agrégées par cellule (jour x slot heure) sur l'ensemble des salles sélectionnées
        $minutesCellTotal = [];
        foreach ($days as $d) {
            $minutesCellTotal[$d] = array_fill_keys($hours, 0);
        }

        // Minutes agrégées par salle (pour l'histogramme)
        $minutesBySalle = [];
        foreach ($selectedSalleIds as $sid) {
            $minutesBySalle[$sid] = 0;
        }

        $totalMinutes = 0;

        // Minutes par type (comparatif)
        $minutesByType = [
            'cours' => 0,
            'td' => 0,
            'tp' => 0,
        ];

        foreach ($rows as $r) {
            $salleId = (int) $r->salle_id;
            if (!isset($minutesBySalle[$salleId])) {
                // quand salle_id filtre = null, mais on garde sélection sur distinct
                continue;
            }

            $jour = $r->jour;
            if (!isset($minutesCellTotal[$jour])) {
                continue;
            }

            $startMin = $this->timeToMinutes($r->heure_debut);
            $endMin = $this->timeToMinutes($r->heure_fin);
            if ($endMin <= $startMin) {
                continue;
            }

            $type = (string) $r->type_seance;
            if (isset($minutesByType[$type])) {
                // On coupe aux bornes d'affichage (sinon on compare des minutes "invisibles" hors heatmap)
                $typeMinutesOverlap = max(0, min($endMin, $rangeEndExclusive) - max($startMin, $rangeStart));
                $minutesByType[$type] += $typeMinutesOverlap;
            }

            // Ajouter les minutes d'occupation par slot horaire (superposition)
            foreach ($hours as $hour) {
                $slotStart = $hour * 60;
                $slotEnd = ($hour + 1) * 60;

                $overlap = max(0, min($endMin, $slotEnd) - max($startMin, $slotStart));
                if ($overlap <= 0) {
                    continue;
                }

                $minutesCellTotal[$jour][$hour] += $overlap;
                $minutesBySalle[$salleId] += $overlap;
                $totalMinutes += $overlap;
            }
        }

        $hoursCount = count($hours);
        $daysCount = count($days);
        $possibleMinutesPerRoom = $daysCount * $hoursCount * 60;
        $possibleMinutesOverall = $roomsCount * $possibleMinutesPerRoom;
        $avgOccupationPct = $possibleMinutesOverall > 0 ? ($totalMinutes / $possibleMinutesOverall) * 100 : 0;

        // HeatmapZ : matrice [jour][hour] -> taux 0..100
        $heatmapZ = [];
        foreach ($days as $d) {
            $row = [];
            foreach ($hours as $hour) {
                $minutesCell = $minutesCellTotal[$d][$hour] ?? 0;
                $cellPossible = $roomsCount * 60; // 1h * nombre salles
                $pct = $cellPossible > 0 ? ($minutesCell / $cellPossible) * 100 : 0;
                $row[] = min(100, round($pct, 2));
            }
            $heatmapZ[] = $row;
        }

        // Occupation moyenne par salle (histogramme)
        $roomRates = [];
        foreach ($minutesBySalle as $sid => $minutesSalle) {
            $rate = $possibleMinutesPerRoom > 0 ? ($minutesSalle / $possibleMinutesPerRoom) * 100 : 0;
            $roomRates[$sid] = $rate;
        }

        $sallesById = Salle::whereIn('id', array_keys($minutesBySalle))
            ->get(['id', 'nom'])
            ->keyBy('id');

        arsort($roomRates);
        $top = array_slice($roomRates, 0, 10, true);

        $roomBar = [
            'labels' => array_map(fn ($sid) => ($sallesById[$sid]->nom ?? ('Salle ' . $sid)), array_keys($top)),
            'rates' => array_values($top),
        ];

        // Occupation moyenne par jour (comparatif)
        $dayRates = [];
        foreach ($days as $d) {
            $minutesDay = array_sum(array_values($minutesCellTotal[$d] ?? []));
            $cellPossible = $roomsCount * $hoursCount * 60;
            $rate = $cellPossible > 0 ? ($minutesDay / $cellPossible) * 100 : 0;
            $dayRates[$d] = $rate;
        }
        $dayBar = [
            'labels' => array_keys($dayRates),
            'rates' => array_values($dayRates),
        ];

        // Occupation par type (comparatif)
        $typePossible = $possibleMinutesOverall; // même possible global
        $typeRates = [];
        foreach (['cours', 'td', 'tp'] as $t) {
            $rate = $typePossible > 0 ? ($minutesByType[$t] / $typePossible) * 100 : 0;
            $typeRates[$t] = $rate;
        }

        $typeLabels = [
            'cours' => 'Cours',
            'td' => 'TD',
            'tp' => 'TP',
        ];

        $typeBar = [
            'labels' => array_map(fn ($t) => $typeLabels[$t] ?? $t, array_keys($typeRates)),
            'rates' => array_values($typeRates),
        ];

        return [
            'heatmapZ' => $heatmapZ,
            'roomBar' => $roomBar,
            'dayBar' => $dayBar,
            'typeBar' => $typeBar,
            'totalMinutes' => $totalMinutes,
            'avgOccupationPct' => $avgOccupationPct,
        ];
    }
}

