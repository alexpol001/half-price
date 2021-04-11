let Swal = require('admin-lte/plugins/sweetalert2/sweetalert2.all');
// $.fn.dataTable.ext.errMode = 'none';
$('body').on('click', '.dataTable .icheck input', function () {
    if (!$(this).val()) {
        let checkbox = $(this).closest('.dataTable').find('.icheck input');
        if ($(this).is(':checked')) {
            checkbox.prop("checked", true)
        } else {
            checkbox.prop("checked", false)
        }
    } else {
        $('#check, #check0').prop('checked', false);
    }
});

$('.data-table .multi-delete').click(function (event) {
    event.preventDefault();
    let dataTable = $(this).closest('.data-table');
    let checkbox = dataTable.find('.icheck input:checked');
    let data = {};
    data.ids = [];
    checkbox.each((i, check) => {
        let val = $(check).val();
        if (val) {
            data['ids'].push(val);
        }
    });
    let messages = {
        confirm : 'Вы действительно хотите окончательно удалить вабранные ('+data.ids.length+') элементы?',
        success: 'Выбранные элементы успешно удалены.',
    };
    deleteRow(dataTable.data('route')+'/multi-delete', data, messages, () => {
        checkbox.prop("checked", false);
    });
});

$('.data-table').on('click', '.delete', function (event) {
    event.preventDefault();
    let dataTable = $(this).closest('.data-table');
    let data = {};
    data.id = $(this).attr('href');
    let messages = {
        confirm : 'Вы действительно хотите окончательно удалить данный элемент?',
        success: 'Элемент успешно удален.',
    };
    deleteRow(dataTable.data('route')+'/delete', data, messages);
});

function deleteRow(url, data, messages, callback) {
    data._token = $('meta[name="csrf-token"]').attr('content');
    let is_error = false;
    Swal.fire({
        title: 'Подтверждение',
        text: messages.confirm,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Нет, отмена!',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            $.ajax({
                async: false,
                type: 'DELETE',
                url: url,
                data: data,
                error: (error) => {
                    Swal.showValidationMessage(
                        'Во время удаления возникла ошибка: ' + error.responseJSON.message
                    );
                    is_error = true;
                },
            });
            return !is_error;
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.value) {
            Swal.fire(
                'Удалено!',
                messages.success,
                'success'
            );
            if (callback) {
                callback();
            }
            let tables = $($.fn.dataTable.tables(false)).DataTable();
            tables.ajax.reload();
        }
    });
}
