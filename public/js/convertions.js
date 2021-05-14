var key = 'd1b2975620847bdec77d719c7f88f78e';
var urlPattern = 'http://api.exchangeratesapi.io/v1/latest?access_key=' + key;

var page = 1;

$(function () {
    findValues();
    loadPage();
});

function loadPage() {
    $.ajax({
        url: '/api/convertions/index?page=' + page,
        dataType: 'json',
        contentType: 'application/json',
        success: function (data) {
            mountButton(data);
            $('#table>tbody').empty();
            for (i = 0; i < data.data.length; i++) {
                var line = mountTable(data.data[i]);
                $('#table>tbody').append(line);
            }
        },
    });
}

function changePage(indice) {
    page = indice;
    loadPage();
}

function mountButton(data) {
    $('#mountButton').empty();
    if (page > 1) {
        var button = '<button type="button" id="prev" class="btn btn-secondary" onclick="changePage(' + (page - 1) + ')">Prev</button>';
        $('#mountButton').append(button);
    }
    for (i = 0; i <= (Math.ceil(data.total) / 10); i++) {
        value = i + 1;
        var button = '<button type="button" id="page' + value + '" class="btn btn-secondary" onclick="changePage(' + value + ')">' + value + '</button>';
        $('#mountButton').append(button);
    }
    if (page * 10 < data.total) {
        var button = '<button type="button" id="next" class="btn btn-secondary" onclick="changePage(' + (page + 1) + ')">Next</button>';
        $('#mountButton').append(button);
    }

}

function mountTable(data) {
    var valueFrom = data.currency_from.toLocaleString('pt-br', { maximumFractionDigits: 2 });
    var valueTo = data.currency_to.toLocaleString('pt-br', { maximumFractionDigits: 2 });
    var total = data.cambio.toLocaleString('pt-br', { maximumFractionDigits: 2 });
    var date = new Date(data.created_at);

    var completeDate = (date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear());

    line = '<tr>' +
        '<td>' + data.currency_from + '</td>' +
        '<td>' + data.currency_to + '</td>' +
        '<td>' + total + '</td>' +
        '<td>' + completeDate + '</td>' +
        '</tr>';

    return line;
}

function saveConversion() {
    var form = document.getElementById('formCreate');
    var formData = new FormData(form);
    $.ajax({
        type: 'post',
        url: '/api/convertions/create',
        data: formData,
        contentType: false,
        processData: false,
        success: function (result) {
            if (!result.resul) {
                alert('Ocorreu um erro na gravação da conversão');
                return;
            }
            alert(result.result.message);
            loadPage();
        }
    });
}

function findValues() {
    $.ajax({
        url: urlPattern,
        dataType: 'jsonp',
        success: function (data) {
            for (i = 0; i < Object.keys(data.rates).length; i++) {
                var key = Object.keys(data.rates)[i];
                var option = mountLine(key);
                $('#currency_to').append(option);
                if (key == 'BRL' || key == 'USD' || key == 'CAD') {
                    $('#currency_from').append(option);
                }
            }
        }
    });
}

function mountLine(key) {
    var option = '<option value=' + key + '>' + key + '</option>';
    return option;
}

function validation() {
    var success = true;

    $('#error_currency_from').html('');
    $('#error_currency_to').html('');

    if ($('#currency_from').val() == '') {
        success = false;
        $('#error_currency_from').html('escolha uma opção que deseja converter (entrada)');
    }
    if ($('#currency_to').val() == '') {
        success = false;
        $('#error_currency_to').html('escolha uma opção que deseja converter (Saida)');
    }
    return success;
}
