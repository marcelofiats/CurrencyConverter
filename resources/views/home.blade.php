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
                                <input type="text" class="form-control" id="cambio" name="cambio">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

    $(function(){
        findValues();
        loadPage();
    });

    function loadPage(){
        $.ajax({
            url: '/api/convertions/index',
            dataType: 'json',
            contentType: 'application/json',
            success: function(data){
                $('#table>tbody').empty();
                for (i = 0; i < data.length; i++) {
                    var line = mountTable(data[i]);
                    $('#table>tbody').append(line);
                }
            }
        });
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
            success: function(){
                loadPage();
            }
        });
    }

    function findValues() {
        var url = 'http://api.exchangeratesapi.io/v1/latest?access_key=0de63256844154c49915583b3070b2df'
        $.ajax({
            url: url,
            dataType: 'jsonp',
            success: function(data){
                console.log(data.rates[Object.keys(data.rates)[0]]);
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
        var keyFrom = document.getElementById('currency_from').options[document.getElementById('currency_from').selectedIndex].innerText;
        var keyTo = document.getElementById('currency_to').options[document.getElementById('currency_to').selectedIndex].innerText;

        var valueFrom = $('#currency_from').val();
        var valueTo = $('#currency_to').val();

        var url = 'http://api.exchangeratesapi.io/v1/latest?access_key=0de63256844154c49915583b3070b2df';
        var base = '&base=' + keyFrom;
        url +=base;

        var symbol = keyTo;

        $.ajax({
            url: url,
            dataType: 'jsonp',
            success: function(data){
                var convertion = data.rates[keyTo];
                convertion = convertion.toLocaleString('pt-br',{maximumFractionDigits: 2});
                $('#cambio').val(convertion);
                $('#currency_from').val(keyFrom);
                $('#currency_to').val(keyTo);
                $('#result').html('Resultado:  1 ' + keyFrom + ' = ' + convertion + ' ' + keyTo);
            }
        });

        saveConversion();
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
