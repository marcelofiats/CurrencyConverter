@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Currency Converter</h3>
                </div>
                <div class="card-body">
                    <form id="formCreate" action="#" method="post">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <label for="currency_from">Escolha a moeda de Origem</label>
                                <select class="form-control" name="currency_from" id="currency_from">
                                    <option value="" selected>Selecione</option>
                                </select>
                                <div id="error_currency_from" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <label for="currency_to">Escolha a moeda de conversão </label>
                                <select class="form-control" onchange="convert()" name="currency_to" id="currency_to">
                                    <option value="" selected>Selecione</option>
                                </select>
                                <div id="error_currency_to" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div id="result"></div>
                                <input type="text" class="form-control" id="cambio" name="cambio" value="">
                            </div>
                        </div>
                    </form>
                    <table id="table" class="table mt-4">
                        <thead>
                            <tr>
                                <th>Moeda Origem</th>
                                <th>Moeda Final</th>
                                <th>Câmbio</th>
                                <th>Data de criação</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div id="mountButton" class="btn-group mr-2" role="group" aria-label="First group">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var key = 'd1b2975620847bdec77d719c7f88f78e';
    var urlPattern = 'http://api.exchangeratesapi.io/v1/latest?access_key=' + key;

    var page = 1;

    $(function(){
        findValues();
        loadPage();
    });

    function loadPage(){
        $.ajax({
            url: '/api/convertions/index?page='+page,
            dataType: 'json',
            contentType: 'application/json',
            success: function(data){
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
        if (page > 1 ) {
            var button = '<button type="button" id="prev" class="btn btn-secondary" onclick="changePage('+(page-1)+')">Prev</button>';
            $('#mountButton').append(button);
        }
        for(i = 0; i <= (Math.ceil(data.total)/10); i++) {
            value = i + 1;
            var button = '<button type="button" id="page'+value+'" class="btn btn-secondary" onclick="changePage('+value+')">'+value+'</button>';
            $('#mountButton').append(button);
        }
        if (page * 10 < data.total) {
            var button = '<button type="button" id="next" class="btn btn-secondary" onclick="changePage('+(page+1)+')">Next</button>';
            $('#mountButton').append(button);
        }

    }

    function mountTable(data) {
        var valueFrom = data.currency_from.toLocaleString('pt-br',{maximumFractionDigits: 2});
        var valueTo = data.currency_to.toLocaleString('pt-br',{maximumFractionDigits: 2});
        var total = data.cambio.toLocaleString('pt-br',{maximumFractionDigits: 2});
        var date = new Date(data.created_at);

        var completeDate = (date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear());

        line = '<tr>' +
                    '<td>' + data.currency_from + '</td>'+
                    '<td>' + data.currency_to + '</td>'+
                    '<td>' + total + '</td>'+
                    '<td>' + completeDate + '</td>'+
               '</tr>';

        return line;
    }

    function saveConversion(){
        var form = document.getElementById('formCreate');
        var formData = new FormData(form);
        $.ajax({
            type: 'post',
            url: '/api/convertions/create',
            data: formData,
            contentType: false,
            processData: false,
            success: function(result){
                if (! result.resul) {
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
            success: function(data){
                for( i = 0; i < Object.keys(data.rates).length; i++) {
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

    function mountLine(key){
        var option = '<option value='+ key +'>'+ key +'</option>';
        return option;
    }

    function convert(){
        if (! validation()) {
            return false
        }
        var valueFrom = $('#currency_from').val();
        var valueTo = $('#currency_to').val();

        var base = '&base=' + valueFrom;
        var url = urlPattern + base;

        $.ajax({
            url: url,
            dataType: 'jsonp',
            contentType: 'application/json',
            async: false,
            success: function(data){
                var convertion = data.rates[valueTo];
                $('#cambio').val(convertion);
                convertion = convertion.toLocaleString('pt-br',{maximumFractionDigits: 2});
                $('#currency_from').val(valueFrom);
                $('#currency_to').val(valueTo);
                $('#result').html('Resultado:  1 ' + valueFrom + ' = ' + convertion + ' ' + valueTo);
                saveConversion();
            }
        }).fail(function(error){
            aler('Ocorreu um erro na execução' + error);
        });
    }

    function validation(){
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
</script>
@endsection
