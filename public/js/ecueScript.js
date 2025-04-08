$('#socioenvdimension').select2({
    theme: "bootstrap-5",
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
    closeOnSelect: false,
});


function updateEcue(id)
{
    let formData = $('#form-info').serialize();
    let form = $('#form-info')[0];
    $('#form-info button').prop('disabled', true);
    if (form.checkValidity()) {
        $.ajax({
            url: 'index.php?page=modifierecue&id=' + id,
            method: 'POST',
            data: {
                action:"modifierEcue",
                formData:formData
            },
            dataType: 'json',
            success: function (res) {
                // res =JSON.parse(res);
                console.log(res);
                if (res.status === 'success') {
                    showInfoModal("ECUE a été modifié avec succès.", "success");
                } else {
                    showInfoModal("La modification de l'ECUE a échoué.", "error");
                }
                $('#form-info button').prop('disabled', false);

            },
            error: function (xhr, status, error) {
                console.error("An error occurred: " + error);
            }
        });
    } else {
        form.reportValidity();
    }

}

function ajouterEcue(id)
{
    let form = $('#form-info')[0];
    let formData = $('#form-info').serialize();

    if (form.checkValidity()) {
        $('#newECUEBtn').prop('disabled', true);
        $.ajax({
            url: 'index.php?page=ajouterecue&id=' + id,
            method: 'POST',
            data: {
                action:"ajouterEcue",
                formData:formData
            },
            success: function (res) {
                if (res.status === 'success') {
                    showInfoModal("Nouvel ECUE ajouté avec succès !", "success")
                    setTimeout(() => {
                        window.location.href = "index.php?page=modifierue&id=" + id;
                    }, 1500);
                } else {
                    showInfoModal("Erreur lors de l'ajout du nouvel ECUE !", "error")
                }
                $('#newECUEBtn').prop('disabled', false);
            },
            error: function (xhr, status, error) {
                console.error("An error occurred: " + error);
            }
        });
    } else {
        form.reportValidity();
    }

}


$(document).ready(function () {
    const textfields = document.querySelectorAll('.textfield');

    textfields.forEach(textfield => {
        const textarea = textfield.querySelector('textarea');
        const currentLength = textfield.querySelector('span');
        currentLength.textContent = `${textarea.value.length}`;
        textarea.addEventListener('input', function () {
            currentLength.textContent = `${this.value.length}`;
        });
    });

    const numberfields = document.querySelectorAll('.numberfield');

    numberfields.forEach(numberfield => {
        numberfield.addEventListener('input', function (e) {
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
            };
        });
    });


});