$(document).ready(function () {
    $('#formInfo').submit(function (event) {
        info(event);
    });

    $('#modalInfo').on('hidden.bs.modal', function () {
        $('#responce').html(
                'Пожалуйста подождите...'
                );
    });
});

function info(event) {
    event.preventDefault();
    var formData = $(event.currentTarget).serialize();
    $('#modalInfo').modal('show');
    $.ajax({
        url: '/cart/request_info',
        type: 'PUT',
        data: formData,
        success: function (data) {
            $('#responce').html(
                    '<span class="label label-' + data['responce']['code'] + '">' + data['responce']['message'] + '</span>'
                    );
        },
        error: function (data) {
            $('#responce').html(
                    '<span class="label label-danger">Произошла ошибка попробуйте позже</span>'
                    );
        }
    });
}