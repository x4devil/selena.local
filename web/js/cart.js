$(document).ready(function () {
    $('.addForm').submit(function (event) {
        add(event);
    });
});

function add(event) {
    console.log(event.data);
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

function login(event) {
    event.preventDefault();
    var login = $('#login-login').val();
    var password = $('#login-password').val();
    $.ajax({
        url: '/login',
        type: 'PUT',
        data: {'login-login': login, 'login-password': password},
        success: function (data) {
            $('#responceLogin').html(
                    '<span class="label label-' + data['responce']['code'] + '">' + data['responce']['message'] + '</span>'
                    );
            $('#login-login').val('');
            $('#login-password').val('');

            if (data['responce']['code'] === 'success') {
                $('#modalLogin').modal('hide');
                $('#loginUser').html('Вы вошли как:' + data['responce']['user']);
            }
        },
        error: function (response) {
            '<span class="label label-error">Произошла ошибка попробуйте позже</span>'
        }
    });
}

