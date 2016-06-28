$(document).ready(function () {
    $('#checkForm').submit(function (event) {
        add(event);
    });

    $('#modalCheck').on('hidden.bs.modal', function () {
        window.location.href = "/catalog";
    });
});

function add(event) {
    event.preventDefault();
    var formData = $(event.currentTarget).serialize();
    $('#modalCheck').modal('show');

    $.ajax({
        url: '/cart/check',
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

