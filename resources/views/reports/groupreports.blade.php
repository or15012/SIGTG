@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGTG - FIA
        @endslot
        @slot('title')
            Welcome !
        @endslot
    @endcomponent

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

    </div>

    <div class="row">

        <div class="col-xl-4 ">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="text-center py-3">
                        <ul class="bg-bubbles ps-0">
                            <li><i class="bx bx-grid-alt font-size-24"></i></li>
                            <li><i class="bx bx-tachometer font-size-24"></i></li>
                            <li><i class="bx bx-store font-size-24"></i></li>
                            <li><i class="bx bx-cube font-size-24"></i></li>
                            <li><i class="bx bx-cylinder font-size-24"></i></li>
                            <li><i class="bx bx-command font-size-24"></i></li>
                            <li><i class="bx bx-hourglass font-size-24"></i></li>
                            <li><i class="bx bx-pie-chart-alt font-size-24"></i></li>
                            <li><i class="bx bx-coffee font-size-24"></i></li>
                            <li><i class="bx bx-polygon font-size-24"></i></li>
                        </ul>
                        <div class="main-wid position-relative">
                            <h3 class="text-white">SIGTG - FIA Dashboard</h3>

                            <h3 class="text-white mb-0"> Bienvenido {{ Auth::user()->first_name }}
                                {{ Auth::user()->last_name }}</h3>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center">
                            <h5 class="card-title mb-0">Reporte por Grupos de Trabajo</h5>
                            <div class="ms-auto">
                                <div class="mb-3">
                                    <button id="export-status-excel-btn" class="btn btn-primary mt-3">Exportar</button>
                                </div>
                                <div class="mb-3">
                                    <label for="cycle-select" class="form-label">Seleccionar Ciclo:</label>
                                    <select class="form-select" id="cycle-select5" onchange="updateData5()">
                                        <option value="" selected disabled>Seleccione un ciclo</option>
                                        @foreach ($ciclos as $ciclo)
                                            <option value="{{ $ciclo->id }}">{{ $ciclo->number }} - {{ $ciclo->year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <canvas id="student-status"></canvas> <!-- Cambiado el id del canvas -->
                        </div>
                        <script>
                            var miGrafico;
                            document.addEventListener("DOMContentLoaded", function() {
                                var datos = @json($detallesGrupos); 
                        
                                // Preparar los datos para el gráfico
                                var etiquetas = datos.map(function(dato) {
                                    return dato.protocol;
                                });
                        
                                var cantidadEstudiantes = datos.map(function(dato) {
                                    return dato.cantidad_estudiantes;
                                });
                        
                                // Configurar el gráfico
                                var options = {
                                    chart: {
                                        type: 'bar',
                                        height: 350
                                    },
                                    plotOptions: {
                                        bar: {
                                            horizontal: false,
                                            columnWidth: '55%',
                                            endingShape: 'rounded'
                                        },
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    stroke: {
                                        show: true,
                                        width: 2,
                                        colors: ['transparent']
                                    },
                                    xaxis: {
                                        categories: etiquetas
                                    },
                                    yaxis: {
                                        title: {
                                            text: 'Cantidad de Estudiantes'
                                        }
                                    },
                                    fill: {
                                        opacity: 1
                                    },
                                    tooltip: {
                                        y: {
                                            formatter: function(val) {
                                                return val + " estudiantes"
                                            }
                                        }
                                    }
                                };
                        
                                // Renderizar el gráfico
                                miGrafico = new ApexCharts(document.querySelector("#student-status"), options);
                                miGrafico.render();
                            });
                        </script>
                    </div>
                </div>
            </div>


        @endsection
        @section('script')
            <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
            <script src="{{ URL::asset('assets/js/pages/chartjs.js') }}"></script>
            {{-- <script src="{{ URL::asset('assets/js/pages/dashboard.init.js') }}"></script> --}}
            <script src="{{ URL::asset('assets/js/app.js') }}"></script>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

            <script>

                function updateData5() {
                    var id = document.getElementById('cycle-select5').value;
        
                    $.ajax({
                        url: '{{ route('dashboards.status') }}/' + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var nuevosDatos = data.new_datos;
        
                            // Definir etiquetas
                            var etiquetas = ['Inscritos', 'No Inscritos'];
        
                            var datosPorEstado = [];
        
                            let inscritos = 0;
                            let noInscritos = 0;
        
                            nuevosDatos.forEach(element => {
                                // Aquí asumimos que el primer elemento del array es la cantidad de estudiantes inscritos,
                                // y el segundo elemento es la cantidad de estudiantes no inscritos
                                inscritos = element.total_inscritos;
                                noInscritos = element.total_no_inscritos;
                            });
        
                            datosPorEstado = [inscritos, noInscritos];
        
                            miGrafico.data.labels = etiquetas;
                            miGrafico.data.datasets[0].data = datosPorEstado;
        
                            miGrafico.update();
                        }
                    });
                }
        
                $(document).ready(function() {
                    $('#export-status-excel-btn').click(function() {
                        excelstatus();
                    });
                });
        
                document.getElementById('cycle-select5').addEventListener('change', function() {
                    if (this.value) {
                        this.style.borderColor = ''; // Restablecer el color de borde al valor predeterminado
                    }
                });
        
                function excelstatus() {
                    var id = document.getElementById('cycle-select5').value;
        
                    if (!id) {
                        document.getElementById('cycle-select5').style.borderColor = 'red';
                        alert("Por favor, seleccione un ciclo.");
                        return;
                    }
        
                    $.ajax({
                        url: '{{ route('dashboards.excel_status') }}/' + id,
                        type: 'GET',
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(data) {
                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(data);
                            a.href = url;
                            a.download = 'proyectos_estados.xlsx'; // Nombre del archivo
                            document.body.append(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en la solicitud AJAX:", error);
                        }
                    });
                }
            </script>
        @endsection
