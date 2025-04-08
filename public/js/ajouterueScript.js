$(document).ready(function () {
    initializeFilters();

    // Mettre à jour la longueur actuelle de la description
    const textarea = document.getElementById('description');
    const currentLength = document.getElementById('currentLength');
    currentLength.textContent = `${textarea.value.length}`;
    textarea.addEventListener('input', function () {
        currentLength.textContent = `${this.value.length}`;
    });

    // Valider l'entrée ECTS
    const ects = document.getElementById('ects');
    ects.addEventListener('input', function (e) {
        const cursorPosition = this.selectionStart;
        const oldValue = this.value;
        const newValue = oldValue.replace(/[^0-9.]/g, '');

        if (oldValue !== newValue) {
            this.value = newValue;
            const cursorAdjustment = oldValue.length - newValue.length;
            this.setSelectionRange(cursorPosition - cursorAdjustment, cursorPosition - cursorAdjustment);
        }

        if (this.value.split('.').length > 2) {
            const parts = this.value.split('.');
            this.value = parts[0] + '.' + parts.slice(1).join('');
        }
    });

    // Gérer la soumission du formulaire
    $("#ajouterUEForm").on('submit',function (e) {
        e.preventDefault();

        if (this.checkValidity()) {
            ajouterue($(this).serialize());
        } else {
            this.reportValidity();
        }
    });

    // Changer l'année de filtre
    $('#yearFilter').change(async function () {
        let selectedYear = $(this).val();
        if (selectedYear !== "") {
            initializeFilters(selectedYear);
        }
    });

    // Changer le cycle de filtre
    $('#cycleFilter').change(async function () {
        const selectedCycle = $(this).val();
        if (selectedCycle !== "") {
            await loadFilterOptions({ action: 'getSemestreOptions', cycle: selectedCycle }, 'periodeformationFilter');
        }
    });

    // Charger les options de filtre
    function loadFilterOptions(data, targetFilter)
    {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: 'index.php?page=ajouterue',
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function (options) {
                    updateFilter(targetFilter, options);
                    resolve(options);
                },
                error: function (xhr, status, error) {
                    console.error("An error occurred: " + error);
                    reject(error);
                }
            });
        });
    }

    // Mettre à jour le filtre avec les nouvelles options
    function updateFilter(filterId,options)
    {
        var filter = $('#' + filterId);
        filter.empty();

        if (filterId == "departementFilter" || filterId == "optionFilter") {
            filter.append($('<option></option>').attr('value', "").text("aucun"));
        }

        $.each(options, function (index, option) {
            filter.append($('<option></option>').attr('value', option['id']).text(option['libelle']));
        });
    }

    // Initialiser les filtres
    async function initializeFilters(selectedYear=$('#yearFilter').val())
    {
        try {
            await loadFilterOptions({ action: 'getCycleFilterOptions', year: selectedYear }, 'cycleFilter');
            const selectedCycle = $('#cycleFilter').val();
            if (selectedCycle) {
                await loadFilterOptions({ action: 'getSemestreOptions', cycle: selectedCycle }, 'periodeformationFilter');
            }
            await loadFilterOptions({ action: 'getDepartementOptions', year: selectedYear }, 'departementFilter')
            await loadFilterOptions({ action: 'getOptionOptions', year: selectedYear }, 'optionFilter')
        } catch (error) {
            console.error("Error loading filter options:", error);
        }
    }
})

// Ajouter une UE
function ajouterue(formData)
{
    $("#ajouterUEForm input[type='submit']").prop("disabled",true)
    $.ajax({
        url: 'index.php?page=ajouterue',
        method: 'POST',
        data: {
            action:"ajouterue",
            formData :formData
        },
        dataType: 'json',
        success: function (res) {
            res = JSON.parse(res);
            if (res.status === "success") {
                showInfoModal(res.message,res.status)
                setTimeout(() => {
                    window.location.href = "index.php?page=modifierue&id=" + res.idmodule
                },1500)
            } else {
                alert(res.message);
                $("#ajouterUEForm input[type='submit']").prop("disabled",false)
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + error);
            reject(error);
            $("#ajouterUEForm input[type='submit']").prop("disabled",false)
        }
    });
}