$(document).ready(function () {
    $('.addForm').submit(function (event) {
        add(event);
    });
});

function add(event) {
    event.preventDefault();
    var formData = $(event.currentTarget).serialize();
    $.ajax({
        url: '/cart/add',
        type: 'PUT',
        data: formData,
        success: function (data) {
            $('#modalAdd').modal('show');
            $('#responce').html(
                    '<span class="label label-' + data['responce']['code'] + '">' + data['responce']['message'] + '</span>'
                    );
        },
        error: function (data) {
            $('#modalAdd').modal('show');
            $('#responce').html(
                    '<span class="label label-danger">Произошла ошибка попробуйте позже</span>'
                    );
        }
    });
}