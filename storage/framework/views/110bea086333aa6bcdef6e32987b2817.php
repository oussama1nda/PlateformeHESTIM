

<?php $__env->startSection('title', 'Analyse des Données'); ?>
<?php $__env->startSection('page-title', 'Analyse des Données'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header / Résumé -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-start justify-between gap-6 flex-wrap">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Occupation des salles (Heatmap)</h2>
                <p class="text-sm text-gray-600 mt-1">
                    Visualisation basée sur les données réelles de l’emploi du temps
                    (<span class="font-semibold">table</span> `emploi_du_temps`).
                </p>
            </div>
            <div class="flex gap-4 flex-wrap">
                <div class="min-w-[180px]">
                    <p class="text-xs text-gray-500">Salles analysées</p>
                    <p class="text-2xl font-bold text-hestim-blue"><?php echo e($summary['roomsCount'] ?? 0); ?></p>
                </div>
                <div class="min-w-[180px]">
                    <p class="text-xs text-gray-500">Heures planifiées</p>
                    <p class="text-2xl font-bold text-hestim-blue"><?php echo e($summary['scheduledHours'] ?? 0); ?></p>
                </div>
                <div class="min-w-[240px]">
                    <p class="text-xs text-gray-500">Occupation moyenne</p>
                    <p class="text-2xl font-bold text-hestim-blue"><?php echo e($summary['avgOccupationPct'] ?? 0); ?>%</p>
                </div>
                <div class="min-w-[180px]">
                    <p class="text-xs text-gray-500">Nombre de créneaux</p>
                    <p class="text-2xl font-bold text-hestim-blue"><?php echo e($summary['entriesCount'] ?? 0); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="<?php echo e(route('admin.analyse_donnees')); ?>" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Salle</label>
                    <select name="salle_id" class="mt-2 w-full border-gray-300 rounded-lg px-3 py-2 bg-white">
                        <option value="" <?php echo e(empty($salleId) ? 'selected' : ''); ?>>Toutes les salles</option>
                        <?php $__currentLoopData = $salles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($salle->id); ?>" <?php echo e((!empty($salleId) && (int)$salleId === (int)$salle->id) ? 'selected' : ''); ?>>
                                <?php echo e($salle->nom); ?> (<?php echo e($salle->capacite); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Type de séance</label>
                    <select name="type_seance" class="mt-2 w-full border-gray-300 rounded-lg px-3 py-2 bg-white">
                        <option value="" <?php echo e(empty($typeSeance) ? 'selected' : ''); ?>>Tous</option>
                        <option value="cours" <?php echo e(($typeSeance ?? '') === 'cours' ? 'selected' : ''); ?>>Cours</option>
                        <option value="td" <?php echo e(($typeSeance ?? '') === 'td' ? 'selected' : ''); ?>>TD</option>
                        <option value="tp" <?php echo e(($typeSeance ?? '') === 'tp' ? 'selected' : ''); ?>>TP</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Date début</label>
                    <input type="date" name="date_from" value="<?php echo e($dateFrom ?? ''); ?>" class="mt-2 w-full border-gray-300 rounded-lg px-3 py-2 bg-white">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Date fin</label>
                    <input type="date" name="date_to" value="<?php echo e($dateTo ?? ''); ?>" class="mt-2 w-full border-gray-300 rounded-lg px-3 py-2 bg-white">
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <button type="submit" class="bg-hestim-blue text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-800 transition">
                    Appliquer les filtres
                </button>
                <a href="<?php echo e(route('admin.analyse_donnees')); ?>" class="px-4 py-2 rounded-lg border border-gray-300 font-semibold text-gray-700 bg-white hover:bg-gray-50 transition">
                    Réinitialiser
                </a>
                <p class="text-xs text-gray-500">
                    Si `date_debut`/`date_fin` est vide dans la BDD, la période peut ne pas filtrer les données.
                </p>
            </div>
        </form>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between gap-4 mb-4 flex-wrap">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Carte chaleur : occupation par jour et heure</h3>
                    <p class="text-sm text-gray-600 mt-1">Chaque cellule = taux d’occupation moyen (0 à 100%).</p>
                </div>
            </div>
            <div id="heatmapChart" style="width:100%; height:520px;"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Comparatif</h3>
            <div class="space-y-6">
                <div>
                    <p class="text-sm font-semibold text-gray-800 mb-2">Occupation moyenne par jour</p>
                    <div id="dayBarChart" style="width:100%; height:260px;"></div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 mb-2">Occupation par type de séance</p>
                    <div id="typeBarChart" style="width:100%; height:260px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Histogramme : occupation par salle (Top 10)</h3>
                <p class="text-sm text-gray-600 mt-1">Trié par taux d’occupation sur la période filtrée.</p>
            </div>
        </div>
        <div id="roomBarChart" style="width:100%; height:320px;"></div>
    </div>
</div>

<!-- Plotly -->
<script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>

<script>
    (function () {
        const days = <?php echo json_encode($days ?? [], 15, 512) ?>;
        const hours = <?php echo json_encode($hoursLabels ?? [], 15, 512) ?>;
        const heatmapZ = <?php echo json_encode($heatmapZ ?? [], 15, 512) ?>;

        const roomLabels = <?php echo json_encode($roomBar['labels'] ?? [], 15, 512) ?>;
        const roomRates = <?php echo json_encode($roomBar['rates'] ?? [], 15, 512) ?>;

        const dayLabels = <?php echo json_encode($dayBar['labels'] ?? [], 15, 512) ?>;
        const dayRates = <?php echo json_encode($dayBar['rates'] ?? [], 15, 512) ?>;

        const typeLabels = <?php echo json_encode($typeBar['labels'] ?? [], 15, 512) ?>;
        const typeRates = <?php echo json_encode($typeBar['rates'] ?? [], 15, 512) ?>;

        // Heatmap
        const heatmapData = [{
            x: hours,
            y: days,
            z: heatmapZ,
            type: 'heatmap',
            colorscale: 'YlOrRd',
            zmin: 0,
            zmax: 100,
            hovertemplate: '%{y}<br>%{x}<br>Occupation: %{z:.1f}%<extra></extra>'
        }];

        const heatmapLayout = {
            margin: {l: 60, r: 20, t: 20, b: 60},
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,0,0)',
            xaxis: {title: 'Heure', tickangle: -45},
            yaxis: {title: 'Jour', autorange: 'reversed'}
        };

        const heatmapDiv = document.getElementById('heatmapChart');
        if (heatmapDiv && hours.length > 0 && days.length > 0) {
            Plotly.newPlot('heatmapChart', heatmapData, heatmapLayout, {displayModeBar: false, responsive: true});
        } else if (heatmapDiv) {
            heatmapDiv.innerHTML = '<div class="text-gray-500 text-sm">Aucune donnée pour les filtres sélectionnés.</div>';
        }

        // Room bar
        const roomBarData = [{
            x: roomLabels,
            y: roomRates,
            type: 'bar',
            marker: {color: roomRates, colorscale: 'Blues'},
            hovertemplate: '%{x}<br>Occupation: %{y:.1f}%<extra></extra>'
        }];

        const roomBarLayout = {
            margin: {l: 70, r: 20, t: 20, b: 70},
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,0,0)',
            xaxis: {tickangle: -45, title: 'Salle'},
            yaxis: {title: 'Occupation (%)'}
        };

        Plotly.newPlot('roomBarChart', roomBarData, roomBarLayout, {displayModeBar: false, responsive: true});

        // Day bar
        const dayBarData = [{
            x: dayLabels,
            y: dayRates,
            type: 'bar',
            marker: {color: dayRates, colorscale: 'Viridis'},
            hovertemplate: '%{x}<br>Occupation: %{y:.1f}%<extra></extra>'
        }];
        const dayBarLayout = {
            margin: {l: 50, r: 20, t: 20, b: 60},
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,0,0)',
            xaxis: {tickangle: -20, title: 'Jour'},
            yaxis: {title: 'Occupation (%)'}
        };
        Plotly.newPlot('dayBarChart', dayBarData, dayBarLayout, {displayModeBar: false, responsive: true});

        // Type bar
        const typeBarData = [{
            x: typeLabels,
            y: typeRates,
            type: 'bar',
            marker: {color: typeRates, colorscale: 'Reds'},
            hovertemplate: '%{x}<br>Occupation: %{y:.1f}%<extra></extra>'
        }];
        const typeBarLayout = {
            margin: {l: 50, r: 20, t: 20, b: 70},
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor: 'rgba(0,0,0,0)',
            xaxis: {tickangle: -10, title: 'Type'},
            yaxis: {title: 'Occupation (%)'}
        };
        Plotly.newPlot('typeBarChart', typeBarData, typeBarLayout, {displayModeBar: false, responsive: true});
    })();
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Desktop\plateformehestim\resources\views/admin/analyse_donnees/index.blade.php ENDPATH**/ ?>