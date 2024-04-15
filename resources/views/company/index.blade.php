@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Empresas</div>

                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="input-group">
                                <input type="text" class="form-control zip_code" id="zip_code" placeholder="Informe um CEP...">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-search-company" id="btn-search-company" type="button">Buscar</button>
                                </span>
                            </div>
                        </div>
                    </div>
                
                    <br>

                    <label for="perPage">Itens por Página:</label>
                    <select id="perPage">
                        @for ($i = 10; $i <= 100; $i+=10)
                            <option value="{{$i}}" {{ request('per_page') == $i ? 'selected' : '' }}>{{$i}}</option>
                        @endfor
                    </select>

                    <div class="table-responsible">
                        <table class="table table-striped" id="table-company">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="clickable" data-sort-field="company_name">
                                        <u>Razão Social</u> {!! ($sortField == 'company_name') ? '<i class="fa fa-sort-'.($sortOrder == 'asc' ? 'asc' : 'desc').'"></i>' : '' !!}
                                    </th>
                                    <th>Nome Fantasia</th>
                                    <th>CNPJ</th>
                                    <th class="clickable" data-sort-field="updated_at">
                                        <u>Última Atualização</u> {!! ($sortField == 'updated_at') ? '<i class="fa fa-sort-'.($sortOrder == 'asc' ? 'asc' : 'desc').'"></i>' : '' !!}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($companies as $company)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $company->company_name }}</td>
                                        <td>{{ $company->trade_name }}</td>
                                        <td>{{ $company->corporate_registry_number }}</td>
                                        <td>{{ date('d/m/Y H:i:s', strtotime($company->updated_at)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">Nenhuma Empresa Cadastrada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-info float-right">
                        {{ $companies->appends(request()->query())->links() }}
                    </div>

                    <div class="pagination-info">
                        <p>Mostrando {{ $companies->firstItem() }} - {{ $companies->lastItem() }} de {{ $totalRecords }} total.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
@endsection

@section('bottom-js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.zip_code').mask('00000-000');

            const base_url = "{{ route('company.index') }}";

            $('.clickable').on('click', function() {
                let perPage = $("#perPage").val();
                let sortField = $(this).data('sort-field');
                let sortOrder = sortField === "{{ $sortField }}" && "{{ $sortOrder }}" === 'asc' ? 'desc' : 'asc';

                let url = `${base_url}?per_page=${perPage}&sort=${sortField}&order=${sortOrder}`;

                window.location = url;
            })

            $("#perPage").on('change', function() {
                let perPage = $(this).val();

                let url = `${base_url}?per_page=${perPage}&sort={{ $sortField }}&order={{ $sortOrder }}`;

                window.location = url;
            });

            $('#btn-search-company').click(function() {
                searchCompanies();
            });

            $('#zip_code').keypress(function(event) {
                if (event.which === 13) {
                    searchCompanies();
                }
            });

            function searchCompanies() {
                let zipCode = $('.zip_code').val();

                $.post('api/company', {
                    'zip_code': zipCode
                })
                .done(function(data) {
                    $('#table-company').empty();

                    if (data.lenfth === 0) {
                        $('#table-company tbody').append('<tr><td colspan="4">Nenhuma empresa encontrada.</td></tr>');
                    } else {
                        console.log(data.data)
                        $.each(data.data, function(index, company) {
                            let row = '<tr>' +
                                '<td>' + index + '</td>' +
                                '<td>' + company.company_name + '</td>' +
                                '<td>' + company.trade_name + '</td>' +
                                '<td>' + company.corporate_registry_number + '</td>' +
                                '</tr>';
                            $('#table-company tbody').append(row);
                        });
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log('Erro ao buscar empresas:', textStatus, errorThrown);
                })
            }
        })
    </script>
@endsection