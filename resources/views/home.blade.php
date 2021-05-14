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
                                <input type="hidden" class="form-control" id="cambio" name="cambio" value="">
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
<script src="{{ asset('js/convertions.js') }}"></script>
@endsection
