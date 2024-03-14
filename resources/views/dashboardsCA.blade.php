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
                                    <h3 class="text-white">SIGTG - FIADashboard</h3>

                                    <h3 class="text-white mb-0"> Bienvenido {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                                    {{--
                                    <p class="text-white-50 px-4 mt-4">Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien libero tincidunt.</p>

                                    <div class="mt-4 pt-2 mb-2">
                                        <a href="" class="btn btn-success">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="avatar">
                                        <span class="avatar-title bg-soft-primary rounded">
                                            <i class="mdi mdi-shopping-outline text-primary font-size-24"></i>
                                        </span>
                                    </div>
                                    <p class="text-muted mt-4 mb-0">Today Orders</p>
                                    <h4 class="mt-1 mb-0">3,89,658 <sup class="text-success fw-medium font-size-14"><i class="mdi mdi-arrow-down"></i> 10%</sup></h4>
                                    <div>
                                        <div class="py-3 my-1">
                                            <div id="mini-1" data-colors='["#3980c0"]'></div>
                                        </div>
                                        <ul class="list-inline d-flex justify-content-between justify-content-center mb-0">
                                            <li class="list-inline-item"><a href="" class="text-muted">Day</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Week</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Month</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Year</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="avatar">
                                        <span class="avatar-title bg-soft-success rounded">
                                            <i class="mdi mdi-eye-outline text-success font-size-24"></i>
                                        </span>
                                    </div>
                                    <p class="text-muted mt-4 mb-0">Today Visitor</p>
                                    <h4 class="mt-1 mb-0">1,648,29 <sup class="text-danger fw-medium font-size-14"><i class="mdi mdi-arrow-down"></i> 19%</sup></h4>
                                    <div>
                                        <div class="py-3 my-1">
                                            <div id="mini-2" data-colors='["#33a186"]'></div>
                                        </div>
                                        <ul class="list-inline d-flex justify-content-between justify-content-center mb-0">
                                            <li class="list-inline-item"><a href="" class="text-muted">Day</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Week</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Month</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Year</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="avatar">
                                        <span class="avatar-title bg-soft-primary rounded">
                                            <i class="mdi mdi-rocket-outline text-primary font-size-24"></i>
                                        </span>
                                    </div>
                                    <p class="text-muted mt-4 mb-0">Total Expense</p>
                                    <h4 class="mt-1 mb-0">6,48,249 <sup class="text-success fw-medium font-size-14"><i class="mdi mdi-arrow-down"></i> 22%</sup></h4>
                                    <div>
                                        <div class="py-3 my-1">
                                            <div id="mini-3" data-colors='["#3980c0"]'></div>
                                        </div>
                                        <ul class="list-inline d-flex justify-content-between justify-content-center mb-0">
                                            <li class="list-inline-item"><a href="" class="text-muted">Day</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Week</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Month</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Year</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="avatar">
                                        <span class="avatar-title bg-soft-success rounded">
                                            <i class="mdi mdi-account-multiple-outline text-success font-size-24"></i>
                                        </span>
                                    </div>
                                    <p class="text-muted mt-4 mb-0">New Users</p>
                                    <h4 class="mt-1 mb-0">$5,265,3 <sup class="text-danger fw-medium font-size-14"><i class="mdi mdi-arrow-down"></i> 18%</sup></h4>
                                    <div>
                                        <div class="py-3 my-1">
                                            <div id="mini-4" data-colors='["#33a186"]'></div>
                                        </div>
                                        <ul class="list-inline d-flex justify-content-between justify-content-center mb-0">
                                            <li class="list-inline-item"><a href="" class="text-muted">Day</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Week</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Month</a></li>
                                            <li class="list-inline-item"><a href="" class="text-muted">Year</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            --}}

            <div class="row">
                {{--
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center mb-3">
                                <h5 class="card-title mb-0">Estudiantes</h5>
                                <div class="ms-auto">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted font-size-12">Sort By:</span> <span class="fw-medium">Weekly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                            <a class="dropdown-item" href="#">Monthly</a>
                                            <a class="dropdown-item" href="#">Yearly</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                            <div class="col-xl-8">
                                <div>
                                        <div id="sales-statistics" data-colors='["#eff1f3","#eff1f3","#eff1f3","#eff1f3","#33a186","#3980c0","#eff1f3","#eff1f3","#eff1f3", "#eff1f3"]' class="apex-chart"></div>
                                </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex">
                                                    <i class="mdi mdi-circle font-size-10 mt-1 text-primary"></i>
                                                    <div class="flex-1 ms-2">
                                                        <p class="mb-0">Product Order</p>
                                                        <h5 class="mt-1 mb-0 font-size-16">43,541.58</h5>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="badge badge-soft-primary">25.4%<i class="mdi mdi-arrow-down ms-2"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 border-top pt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex">
                                                    <i class="mdi mdi-circle font-size-10 mt-1 text-primary"></i>
                                                    <div class="flex-1 ms-2">
                                                        <p class="mb-0">Product Pending</p>
                                                        <h5 class="mt-1 mb-0 font-size-16">17,351.12</h5>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="badge badge-soft-primary">17.4%<i class="mdi mdi-arrow-down ms-2"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 border-top pt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex">
                                                    <i class="mdi mdi-circle font-size-10 mt-1 text-success"></i>
                                                    <div class="flex-1 ms-2">
                                                        <p class="mb-0">Product Cancelled</p>
                                                        <h5 class="mt-1 mb-0 font-size-16">32,569.74</h5>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="badge badge-soft-success">16.3%<i class="mdi mdi-arrow-up ms-1"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 border-top pt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex">
                                                    <i class="mdi mdi-circle font-size-10 mt-1 text-primary"></i>
                                                    <div class="flex-1 ms-2">
                                                        <p class="mb-0">Product Delivered</p>
                                                        <h5 class="mt-1 mb-0 font-size-16">67,356.24</h5>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="badge badge-soft-primary">65.7%<i class="mdi mdi-arrow-up ms-1"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                --}}
                <style>
                    #groups-protocol,
                    #extensions-protocol,
                    #students-course {
                        height: 400px;
                    }
                </style>


                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center">
                                <h5 class="card-title mb-0">Grupos por escuela</h5>
                                <div class="ms-auto">
                                <div class="mb-3">
                                    <button id="export-groups-excel-btn" class="btn btn-success mt-3">Exportar grupos según escuela</button>
                                </div>
                                <div class="mb-3">
                                    <label for="cycle-select3" class="form-label">Seleccionar Ciclo:</label>
                                    <select class="form-select" id="cycle-select3"  onchange="updateData3()">
                                    <option value="" selected disabled>Seleccione un ciclo</option>
                                        @foreach($ciclos as $ciclo)
                                            <option value="{{ $ciclo->id }}">{{$ciclo->number}} - {{$ciclo->year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                    <div id="groups-protocol" ></div>
                            </div>


                            <div class="d-flex flex-wrap align-items-center">
                                <h5 class="card-title mb-0">Extensiones por escuela</h5>
                                <div class="ms-auto">
                                <div class="mb-3">
                                    <button id="export-extensions-excel-btn" class="btn btn-success mt-3">Exportar extensiones según escuela</button>
                                </div>
                                <div class="mb-3">
                                    <label for="cycle-select4" class="form-label">Seleccionar Ciclo:</label>
                                    <select class="form-select" id="cycle-select4"  onchange="updateData4()">
                                    <option value="" selected disabled>Seleccione un ciclo</option>
                                        @foreach($ciclos as $ciclo)
                                            <option value="{{ $ciclo->id }}">{{$ciclo->number}} - {{$ciclo->year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            </div>


                            <div class="text-center mt-4">
                                    <div id="extensions-protocol"></div>
                            </div>

                            <script>
                                var chart1;
                                document.addEventListener("DOMContentLoaded", function () {
                                    var datos = @json($datos3);

                                    //datos
                                    var resultados = {};

                                    var escuelasUnicas = Array.from(new Set(datos.map(dato => dato.school_name)));

                                    var coloresEscuelas = {};

                                    datos.forEach(function(dato) {
                                        var protocolo = dato.protocol_name;
                                        var escuela = dato.school_name;
                                        var cantidad = dato.cantidad;

                                        if (!resultados[protocolo]) {
                                            resultados[protocolo] = {
                                                name: protocolo,
                                                data: Array(escuelasUnicas.length).fill(0),
                                                color: obtenerColorEscuela(escuela)
                                            };
                                        }

                                        var indiceEscuela = escuelasUnicas.indexOf(escuela);

                                        resultados[protocolo].data[indiceEscuela] = cantidad;
                                    });

                                    var resultadosArray = Object.values(resultados);

                                    console.log(resultadosArray);

                                  
                                    //Etiquetas
                                    var datosPorEscuela = {};

                                    datos.forEach(function (dato) {
                                        if (!datosPorEscuela[dato.school_name]) {
                                            datosPorEscuela[dato.school_name] = {};
                                        }
                                        datosPorEscuela[dato.school_name][dato.protocol_name] = dato.cantidad;
                                    });

                                    var etiquetas = Object.keys(datosPorEscuela);


                                    // Definir colores personalizados
                                    function obtenerColorEscuela(escuela) {
                                        if (!coloresEscuelas.hasOwnProperty(escuela)) {
                                            // Generar un nuevo color si la escuela no tiene uno asignado
                                            coloresEscuelas[escuela] = obtenerColorAleatorio();
                                        }
                                        return coloresEscuelas[escuela];
                                    }

                                    // Función para obtener un color aleatorio
                                    function obtenerColorAleatorio() {
                                        var letras = '0123456789ABCDEF';
                                        var color = '#';
                                        for (var i = 0; i < 6; i++) {
                                            color += letras[Math.floor(Math.random() * 16)];
                                        }
                                        return color;
                                    }

                                    var options = {
                                        chart: {
                                            type: 'bar',
                                            height: 350,
                                            toolbar: {
                                                show: false
                                            }
                                        },
                                        plotOptions: {
                                            bar: {
                                                horizontal: false,                 
                                                columnWidth: '35%',
                                                endingShape: 'rounded'
                                            },
                                        },
                                        dataLabels: {
                                            enabled: true
                                        },
                                        stroke: {
                                            show: true,
                                            width: 1,
                                            colors: ['transparent']
                                        },
                                        series: resultadosArray,
                                        xaxis: {
                                            categories: etiquetas,
                                        },
                                        legend: {
                                            show: false
                                        },
                                        fill: {
                                            opacity: 1
                                        }
                                    };

                                    chart1 = new ApexCharts(document.querySelector("#groups-protocol"), options);
                                    chart1.render();
                                });

                                var chart2;
                                document.addEventListener("DOMContentLoaded", function () {
                                    var datos = @json($datos4);

                                    //datos
                                    var resultados = {};

                                    var escuelasUnicas = Array.from(new Set(datos.map(dato => dato.school_name)));

                                    var coloresEscuelas = {};

                                    datos.forEach(function(dato) {
                                        var protocolo = dato.protocol_name;
                                        var escuela = dato.school_name;
                                        var cantidad = dato.cantidad;

                                        if (!resultados[protocolo]) {
                                            resultados[protocolo] = {
                                                name: protocolo,
                                                data: Array(escuelasUnicas.length).fill(0),
                                                color: obtenerColorEscuela(escuela)
                                            };
                                        }

                                        var indiceEscuela = escuelasUnicas.indexOf(escuela);

                                        resultados[protocolo].data[indiceEscuela] = cantidad;
                                    });

                                    var resultadosArray = Object.values(resultados);

                                    console.log(resultadosArray);

                                  
                                    //Etiquetas
                                    var datosPorEscuela = {};

                                    datos.forEach(function (dato) {
                                        if (!datosPorEscuela[dato.school_name]) {
                                            datosPorEscuela[dato.school_name] = {};
                                        }
                                        datosPorEscuela[dato.school_name][dato.protocol_name] = dato.cantidad;
                                    });

                                    var etiquetas = Object.keys(datosPorEscuela);


                                    // Definir colores personalizados
                                    function obtenerColorEscuela(escuela) {
                                        if (!coloresEscuelas.hasOwnProperty(escuela)) {
                                            // Generar un nuevo color si la escuela no tiene uno asignado
                                            coloresEscuelas[escuela] = obtenerColorAleatorio();
                                        }
                                        return coloresEscuelas[escuela];
                                    }

                                    // Función para obtener un color aleatorio
                                    function obtenerColorAleatorio() {
                                        var letras = '0123456789ABCDEF';
                                        var color = '#';
                                        for (var i = 0; i < 6; i++) {
                                            color += letras[Math.floor(Math.random() * 16)];
                                        }
                                        return color;
                                    }

                                    var options = {
                                        chart: {
                                            type: 'bar',
                                            height: 350,
                                            toolbar: {
                                                show: false
                                            }
                                        },
                                        plotOptions: {
                                            bar: {
                                                horizontal: false,                 
                                                columnWidth: '35%',
                                                endingShape: 'rounded'
                                            },
                                        },
                                        dataLabels: {
                                            enabled: true
                                        },
                                        stroke: {
                                            show: true,
                                            width: 1,
                                            colors: ['transparent']
                                        },
                                        series: resultadosArray,
                                        xaxis: {
                                            categories: etiquetas,
                                        },
                                        legend: {
                                            show: false
                                        },
                                        fill: {
                                            opacity: 1
                                        }
                                    };

                                    chart2 = new ApexCharts(document.querySelector("#extensions-protocol"), options);
                                    chart2.render();
                                });


                            </script>
                        </div>
                    </div>
                </div>
            </div>


                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center">
                                <h5 class="card-title mb-0">Estudiantes por protocolo</h5>
                                <div class="ms-auto">
                                <div class="mb-3">
                                    <button id="export-excel-btn" class="btn btn-primary mt-3">Exportar notas según protocolo</button>
                                </div>
                                <div class="mb-3">
                                    <label for="cycle-select" class="form-label">Seleccionar Ciclo:</label>
                                    <select class="form-select" id="cycle-select"  onchange="updateData()">
                                    <option value="" selected disabled>Seleccione un ciclo</option>
                                        @foreach($ciclos as $ciclo)
                                            <option value="{{ $ciclo->id }}">{{$ciclo->number}} - {{$ciclo->year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                    <canvas id="students-protocol"></canvas>
                            </div>
                            <script>
                                var miGrafico;
                                document.addEventListener("DOMContentLoaded", function () {
                                    var datos = @json($datos); 

                                    var etiquetas = datos.map(function (elemento) {
                                        return elemento.protocol_name + ' (' + elemento.cycle_number + '-' + elemento.cycle_year+')';
                                    });

                                    var datosEstudiantes = datos.map(function (elemento) {
                                        return elemento.cantidad_estudiantes;
                                    });
                                    var colores = datos.map(function () {
                                        return 'rgba(' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ', 1)';
                                    });

                                    var ctx = document.getElementById('students-protocol').getContext('2d');
                                    miGrafico = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: etiquetas,
                                            datasets: [{
                                                label: 'Cantidad de Estudiantes por Protocolo',
                                                data: datosEstudiantes,
                                                backgroundColor: colores,
                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            },
                                            maintainAspectRatio: false,
                                        
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            @if(session('protocol')['id'] != 4)
            <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center">
                                <h5 class="card-title mb-0">Estudiantes por curso</h5>
                                <div class="ms-auto">
                                <div class="mb-3">
                                    <button id="export-excel-btn2" class="btn btn-primary mt-3">Exportar notas curso</button>
                                </div>
                                <div class="mb-3">
                                    <label for="cycle-select2" class="form-label">Seleccionar Ciclo:</label>
                                    <select class="form-select" id="cycle-select2"  onchange="updateData2()">
                                    <option value="" selected disabled>Seleccione un ciclo</option>
                                        @foreach($ciclos as $ciclo)
                                            <option value="{{ $ciclo->id }}">{{$ciclo->number}} - {{$ciclo->year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                    <div id="students-course"></div>
                            </div>
                            <script>
                                var chart3;
                                document.addEventListener("DOMContentLoaded", function () {
                                    var datos = @json($datos2); 

                                    var etiquetas = datos.map(function (elemento) {
                                        return elemento.course_name + ' (' + elemento.cycle_number + '-' + elemento.cycle_year+')';
                                    });

                                    var datosEstudiantes = datos.map(function (elemento) {
                                        return elemento.cantidad_estudiantes;
                                    });

                                    var colores = datos.map(function () {
                                        return 'rgba(' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ', 1)';
                                    });

                                    var options = {
                                        chart: {
                                            type: 'pie',
                                            height: 350
                                        },
                                        labels: etiquetas,
                                        series: datosEstudiantes,
                                        colors: colores,
                                        responsive: [{
                                            breakpoint: 480,
                                            options: {
                                                chart: {
                                                    width: 200
                                                },
                                                legend: {
                                                    position: 'bottom'
                                                }
                                            }
                                        }]
                                    };

                                    chart3 = new ApexCharts(document.querySelector("#students-course"), options);
                                    chart3.render();
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            {{--
            <div class="row">
                <div class="col-xl-8">
                        <div class="row">
                            <div class="col-lg-5">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <h5 class="card-title mb-0">Order Activity</h5>
                                                <div class="ms-auto">
                                                    <div class="dropdown">
                                                        <a class="font-size-16 text-muted dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="mdi mdi-dots-horizontal"></i>
                                                        </a>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#">Action</a>
                                                            <a class="dropdown-item" href="#">Another action</a>
                                                            <a class="dropdown-item" href="#">Something else here</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-0">
                                            <ol class="activity-feed mb-0 px-4" data-simplebar style="max-height: 377px;">
                                                <li class="feed-item">
                                                    <div class="d-flex justify-content-between feed-item-list">
                                                        <div>
                                                            <h5 class="font-size-15 mb-1">Your Manager Posted</h5>
                                                            <p class="text-muted mt-0 mb-0">James Raphael</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-muted mb-0">1 hour ago</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="feed-item">
                                                    <div class="d-flex justify-content-between feed-item-list">
                                                        <div>
                                                            <h5 class="font-size-15 mb-1">You have 5 pending order.</h5>
                                                            <p class="text-muted mt-0 mb-0">Delivered</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-muted mb-0">6 hour ago</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="feed-item">
                                                    <div class="d-flex justify-content-between feed-item-list">
                                                        <div>
                                                            <h5 class="font-size-15 mb-1">New Order Received</h5>
                                                            <p class="text-muted mt-0 mb-0">Pick Up</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-muted mb-0">1 day ago</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="feed-item">
                                                    <div class="d-flex justify-content-between feed-item-list">
                                                        <div>
                                                            <h5 class="font-size-15 mb-1">Your Manager Posted</h5>
                                                            <p class="text-muted mt-0 mb-0">In Transit</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-muted mb-0">Yesterday</p>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="feed-item">
                                                    <div class="d-flex justify-content-between feed-item-list">
                                                        <div>
                                                            <h5 class="font-size-15 mb-1">You have 1 pending order.</h5>
                                                            <p class="text-muted mt-0 mb-0">Dispatched</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-muted mb-0">2 hour ago</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="feed-item pb-1">
                                                    <div class="d-flex justify-content-between feed-item-list">
                                                        <div>
                                                            <h5 class="font-size-15 mb-1">New Order Received</h5>
                                                            <p class="text-muted mt-0 mb-0">Order Received</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-muted mb-0">Today</p>
                                                        </div>
                                                    </div>
                                                </li>

                                            </ol>

                                        </div>
                                    </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Top Users</h4>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a class=" dropdown-toggle" href="#" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="text-muted">All Members<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                                        <a class="dropdown-item" href="#">Members</a>
                                                        <a class="dropdown-item" href="#">New Members</a>
                                                        <a class="dropdown-item" href="#">Old Members</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body px-0 pt-2">
                                        <div class="table-responsive px-3" data-simplebar style="max-height: 393px;">
                                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 20px;"><img src="{{ URL::asset('assets/images/users/avatar-4.jpg') }}" class="avatar-sm rounded-circle " alt="..."></td>
                                                        <td>
                                                            <h6 class="font-size-15 mb-1">Glenn Holden</h6>
                                                            <p class="text-muted font-size-13 mb-0"><i class="mdi mdi-map-marker"></i> Nevada</p>
                                                        </td>
                                                        <td class="text-muted"><i class="icon-xs icon me-2 text-success" data-feather="trending-up"></i>$250.00</td>
                                                        <td><span class="badge badge-soft-danger font-size-12">Cancel</span></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="{{ URL::asset('assets/images/users/avatar-5.jpg') }}" class="avatar-sm rounded-circle " alt="..."></td>
                                                        <td>
                                                            <h6 class="font-size-15 mb-1">Lolita Hamill</h6>
                                                            <p class="text-muted font-size-13 mb-0"><i class="mdi mdi-map-marker"></i> Texas</p>
                                                        </td>
                                                        <td class="text-muted"><i class="icon-xs icon me-2 text-danger" data-feather="trending-down"></i>$110.00</td>
                                                        <td><span class="badge badge-soft-success font-size-12">Success</span></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="{{ URL::asset('assets/images/users/avatar-6.jpg') }}" class="avatar-sm rounded-circle " alt="..."></td>
                                                        <td>
                                                            <h6 class="font-size-15 mb-1">Robert Mercer</h6>
                                                            <p class="text-muted font-size-13 mb-0"><i class="mdi mdi-map-marker"></i> California</p>
                                                        </td>
                                                        <td class="text-muted"><i class="icon-xs icon me-2 text-success" data-feather="trending-up"></i>$420.00</td>
                                                        <td><span class="badge badge-soft-info font-size-12">Active</span></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="{{ URL::asset('assets/images/users/avatar-7.jpg') }}" class="avatar-sm rounded-circle " alt="..."></td>
                                                        <td>
                                                            <h6 class="font-size-15 mb-1">Marie Kim</h6>
                                                            <p class="text-muted font-size-13 mb-0"><i class="mdi mdi-map-marker"></i> Montana</p>
                                                        </td>
                                                        <td class="text-muted"><i class="icon-xs icon me-2 text-danger" data-feather="trending-down"></i>$120.00</td>
                                                        <td><span class="badge badge-soft-warning font-size-12">Pending</span></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="{{ URL::asset('assets/images/users/avatar-8.jpg') }}" class="avatar-sm rounded-circle " alt="..."></td>
                                                        <td>
                                                            <h6 class="font-size-15 mb-1">Sonya Henshaw</h6>
                                                            <p class="text-muted font-size-13 mb-0"><i class="mdi mdi-map-marker"></i> Colorado</p>
                                                        </td>
                                                        <td class="text-muted"><i class="icon-xs icon me-2 text-success" data-feather="trending-up"></i>$112.00</td>
                                                        <td><span class="badge badge-soft-info font-size-12">Active</span></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="{{ URL::asset('assets/images/users/avatar-2.jpg') }}" class="avatar-sm rounded-circle " alt="..."></td>
                                                        <td>
                                                            <h6 class="font-size-15 mb-1">Marie Kim</h6>
                                                            <p class="text-muted font-size-13 mb-0"><i class="mdi mdi-map-marker"></i> Australia</p>
                                                        </td>
                                                        <td class="text-muted"><i class="icon-xs icon me-2 text-danger" data-feather="trending-down"></i>$120.00</td>
                                                        <td><span class="badge badge-soft-success font-size-12">Success</span></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="{{ URL::asset('assets/images/users/avatar-1.jpg') }}" class="avatar-sm rounded-circle " alt="..."></td>
                                                        <td>
                                                            <h6 class="font-size-15 mb-1">Sonya Henshaw</h6>
                                                            <p class="text-muted font-size-13 mb-0"><i class="mdi mdi-map-marker"></i> India</p>
                                                        </td>
                                                        <td class="text-muted"><i class="icon-xs icon me-2 text-success" data-feather="trending-up"></i>$112.00</td>
                                                        <td><span class="badge badge-soft-danger font-size-12">Cancel</span></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> <!-- enbd table-responsive-->
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-header">
                            <div class="align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Top Countries</h4>
                                <div class="flex-shrink-0">
                                    <div class="dropdown">
                                        <a class=" dropdown-toggle" href="#" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted">View All<i class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                            <a class="dropdown-item" href="#">Members</a>
                                            <a class="dropdown-item" href="#">New Members</a>
                                            <a class="dropdown-item" href="#">Old Members</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-1">

                            <div class="table-responsive">
                                <table class="table table-centered align-middle table-nowrap mb-0">

                                    <tbody>
                                        <tr>
                                            <td><img src="{{ URL::asset('assets/images/flags/us.jpg') }}" alt="user-image" class="me-3" height="18">US</td>
                                            <td>
                                                26,568.84
                                            </td>
                                            <td>
                                                <i class="bx bx-trending-up text-success"></i>
                                            </td>
                                            <td>
                                                40%
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><img src="{{ URL::asset('assets/images/flags/germany.jpg') }}" alt="user-image" class="me-3" height="18">German</td>
                                            <td>
                                                36,485.52
                                            </td>
                                            <td>
                                                <i class="bx bx-trending-up text-success"></i>
                                            </td>
                                            <td>
                                                50%
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><img src="{{ URL::asset('assets/images/flags/italy.jpg') }}" alt="user-image" class="me-3" height="18">Italy</td>
                                            <td>
                                                17,568.84
                                            </td>
                                            <td>
                                                <i class="bx bx-trending-down text-danger"></i>
                                            </td>
                                            <td>
                                                20%
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><img src="{{ URL::asset('assets/images/flags/spain.jpg') }}" alt="user-image" class="me-3" height="18">Spain</td>
                                            <td>
                                                75,521.28
                                            </td>
                                            <td>
                                                <i class="bx bx-trending-up text-success"></i>
                                            </td>
                                            <td>
                                                70%
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="card best-product">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-start">
                            <div class="col-lg-8">
                                <h5 class="card-title best-product-title">Best Selling Product</h5>
                                <div class="row align-items-end mt-4">
                                    <div class="col-4">
                                        <div class="mt-1">
                                            <h4 class="font-size-20 best-product-title">2,562</h4>
                                            <p class="text-muted mb-0">Sold</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mt-1">
                                            <h4 class="font-size-20 best-product-title">4,652</h4>
                                            <p class="text-muted mb-0">Stock</p>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="mt-1">
                                            <a href="" class="btn btn-primary btn-sm">Buy
                                                Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                </div>
            </div>--}}

            {{--
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-wrap align-items-center">
                                <h5 class="card-title mb-0">Earnings By Item</h5>
                                <div class="ms-auto">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted font-size-12">Sort By:</span> <span class="fw-medium">Weekly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                            <a class="dropdown-item" href="#">Monthly</a>
                                            <a class="dropdown-item" href="#">Yearly</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-xl-0">
                            <div id="earning-item" data-colors='["#33a186","#3980c0"]' class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-wrap align-items-center">
                                <h5 class="card-title mb-0">Manage Orders</h5>
                                <div class="ms-auto">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted font-size-12">Sort By: </span> <span class="fw-medium"> Weekly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                            <a class="dropdown-item" href="#">Monthly</a>
                                            <a class="dropdown-item" href="#">Yearly</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-xl-1">
                            <div class="table-responsive">
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0">
                                    <thead >
                                        <tr>
                                            <th>No</th>
                                            <th>Product's Name</th>
                                            <th>Variant</th>
                                            <th>Type</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Sales</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td><a href="javascript: void(0);" class="text-body">Iphone 12 Max Pro</a> </td>
                                            <td>
                                                <i class="mdi mdi-circle font-size-10 me-1 align-middle text-secondary"></i> Gray
                                            </td>
                                            <td>
                                                Electronic
                                            </td>
                                            <td>
                                                1,564 Items
                                            </td>
                                            <td>
                                                $1200
                                            </td>
                                            <td>
                                                900
                                            </td>

                                            <td style="width: 130px;">
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="75">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>2.</td>
                                            <td><a href="javascript: void(0);" class="text-body">New Red and White jacket </a> </td>
                                            <td>
                                                <i class="mdi mdi-circle font-size-10 me-1 align-middle text-danger"></i> Red
                                            </td>
                                            <td>
                                                Fashion
                                            </td>
                                            <td>
                                                568 Items
                                            </td>
                                            <td>
                                                $300
                                            </td>
                                            <td>
                                                650
                                            </td>

                                            <td style="width: 130px;">
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="75">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>3.</td>
                                            <td><a href="javascript: void(0);" class="text-body">Latest Series Watch OS 8</a> </td>
                                            <td>
                                                <i class="mdi mdi-circle font-size-10 me-1 align-middle text-primary"></i> Dark
                                            </td>
                                            <td>
                                                Electronic
                                            </td>
                                            <td>
                                                1,232 Items
                                            </td>
                                            <td>
                                                $250
                                            </td>
                                            <td>
                                                350
                                            </td>

                                            <td style="width: 130px;">
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="75">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td><a href="javascript: void(0);" class="text-body">New Horror Book</a> </td>
                                            <td>
                                                <i class="mdi mdi-circle font-size-10 me-1 align-middle text-success"></i> Green
                                            </td>
                                            <td>
                                                Book
                                            </td>
                                            <td>
                                                1,564 Items
                                            </td>
                                            <td>
                                                $1200
                                            </td>
                                            <td>
                                                900
                                            </td>

                                            <td style="width: 130px;">
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="75">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5.</td>
                                            <td><a href="javascript: void(0);" class="text-body">Smart 4k Android TV</a> </td>
                                            <td>
                                                <i class="mdi mdi-circle font-size-10 me-1 align-middle text-primary"></i> Gray
                                            </td>
                                            <td>
                                                Electronic
                                            </td>
                                            <td>
                                                5,632 Items
                                            </td>
                                            <td>
                                                $700
                                            </td>
                                            <td>
                                                600
                                            </td>

                                            <td style="width: 130px;">
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped bg-pricing" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="75">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/chartjs.js') }}"></script>
    {{--<script src="{{ URL::asset('assets/js/pages/dashboard.init.js') }}"></script>--}}
    <script src="{{ URL::asset('assets/js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        function updateData(){
            var id = document.getElementById('cycle-select').value;
            
            $.ajax({
                    url: '{{route('dashboard.proto')}}/'+id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var nuevosDatos = data.new_datos;

                        var etiquetas = nuevosDatos.map(function (elemento) {
                            return elemento.protocol_name + ' (' + elemento.cycle_number + '-' + elemento.cycle_year+')';
                        });

                        var datosEstudiantes = nuevosDatos.map(function (elemento) {
                            return elemento.cantidad_estudiantes;
                        });

                        miGrafico.data.labels = etiquetas;
                        miGrafico.data.datasets[0].data = datosEstudiantes;


                        miGrafico.update();

                    }
                });

        }

        function updateData2(){
            var id = document.getElementById('cycle-select2').value;
            
            $.ajax({
                    url: '{{route('dashboard.course')}}/'+id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var datos = data.new_datos;

                        var nuevasEtiquetas = datos.map(function (elemento) {
                            return elemento.course_name + ' (' + elemento.cycle_number + '-' + elemento.cycle_year+')';
                        });

                        var nuevosDatos = datos.map(function (elemento) {
                            return elemento.cantidad_estudiantes;
                        });



                        chart3.updateOptions({
                            labels: nuevasEtiquetas,
                            series: nuevosDatos
                        });
                    }
                });

        }

        function updateData3(){
            var id = document.getElementById('cycle-select3').value;
            
            $.ajax({
                    url: '{{route('dashboard.group')}}/'+id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var datos = data.new_datos;

                        //datos
                        var resultados = {};

                        var escuelasUnicas = Array.from(new Set(datos.map(dato => dato.school_name)));

                        var coloresEscuelas = {};

                        datos.forEach(function(dato) {
                            var protocolo = dato.protocol_name;
                            var escuela = dato.school_name;
                            var cantidad = dato.cantidad;

                            if (!resultados[protocolo]) {
                                resultados[protocolo] = {
                                    name: protocolo,
                                    data: Array(escuelasUnicas.length).fill(0),
                                    color: obtenerColorEscuela(escuela)
                                };
                            }

                            var indiceEscuela = escuelasUnicas.indexOf(escuela);

                            resultados[protocolo].data[indiceEscuela] = cantidad;
                        });

                        var nuevosDatos = Object.values(resultados);

                        console.log(nuevosDatos);


                        //Etiquetas
                        var datosPorEscuela = {};

                        datos.forEach(function (dato) {
                            if (!datosPorEscuela[dato.school_name]) {
                                datosPorEscuela[dato.school_name] = {};
                            }
                            datosPorEscuela[dato.school_name][dato.protocol_name] = dato.cantidad;
                        });

                        var etiquetas = Object.keys(datosPorEscuela);


                        // Definir colores personalizados
                        function obtenerColorEscuela(escuela) {
                            if (!coloresEscuelas.hasOwnProperty(escuela)) {
                                // Generar un nuevo color si la escuela no tiene uno asignado
                                coloresEscuelas[escuela] = obtenerColorAleatorio();
                            }
                            return coloresEscuelas[escuela];
                        }

                        // Función para obtener un color aleatorio
                        function obtenerColorAleatorio() {
                            var letras = '0123456789ABCDEF';
                            var color = '#';
                            for (var i = 0; i < 6; i++) {
                                color += letras[Math.floor(Math.random() * 16)];
                            }
                            return color;
                        }


                        chart1.updateOptions({
                            series: nuevosDatos
                        });
                    }
                });

        }

        function updateData4(){
            var id = document.getElementById('cycle-select4').value;
            
            $.ajax({
                    url: '{{route('dashboard.group')}}/'+id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var datos = data.new_datos;

                        //datos
                        var resultados = {};

                        var escuelasUnicas = Array.from(new Set(datos.map(dato => dato.school_name)));

                        var coloresEscuelas = {};

                        datos.forEach(function(dato) {
                            var protocolo = dato.protocol_name;
                            var escuela = dato.school_name;
                            var cantidad = dato.cantidad;

                            if (!resultados[protocolo]) {
                                resultados[protocolo] = {
                                    name: protocolo,
                                    data: Array(escuelasUnicas.length).fill(0),
                                    color: obtenerColorEscuela(escuela)
                                };
                            }

                            var indiceEscuela = escuelasUnicas.indexOf(escuela);

                            resultados[protocolo].data[indiceEscuela] = cantidad;
                        });

                        var nuevosDatos = Object.values(resultados);

                        console.log(nuevosDatos);


                        //Etiquetas
                        var datosPorEscuela = {};

                        datos.forEach(function (dato) {
                            if (!datosPorEscuela[dato.school_name]) {
                                datosPorEscuela[dato.school_name] = {};
                            }
                            datosPorEscuela[dato.school_name][dato.protocol_name] = dato.cantidad;
                        });

                        var etiquetas = Object.keys(datosPorEscuela);


                        // Definir colores personalizados
                        function obtenerColorEscuela(escuela) {
                            if (!coloresEscuelas.hasOwnProperty(escuela)) {
                                // Generar un nuevo color si la escuela no tiene uno asignado
                                coloresEscuelas[escuela] = obtenerColorAleatorio();
                            }
                            return coloresEscuelas[escuela];
                        }

                        // Función para obtener un color aleatorio
                        function obtenerColorAleatorio() {
                            var letras = '0123456789ABCDEF';
                            var color = '#';
                            for (var i = 0; i < 6; i++) {
                                color += letras[Math.floor(Math.random() * 16)];
                            }
                            return color;
                        }


                        chart2.updateOptions({
                            series: nuevosDatos
                        });
                    }
                });

        }
        


        $(document).ready(function() {
            $('#export-excel-btn').click(function() {
                excelproto();
            });
            $('#export-excel-btn2').click(function() {
                excelcourse();
            });
            $('#export-groups-excel-btn').click(function(){
                excelgroups();
            })

            $('#export-extensions-excel-btn').click(function(){
                excelextensions();
            })
        });

        document.getElementById('cycle-select').addEventListener('change', function() {
            if (this.value) {
                this.style.borderColor = ''; // Restablecer el color de borde al valor predeterminado
            }
        });

        document.getElementById('cycle-select2').addEventListener('change', function() {
            if (this.value) {
                this.style.borderColor = ''; // Restablecer el color de borde al valor predeterminado
            }
        });

        document.getElementById('cycle-select3').addEventListener('change', function() {
            if (this.value) {
                this.style.borderColor = ''; // Restablecer el color de borde al valor predeterminado
            }
        });

        document.getElementById('cycle-select4').addEventListener('change', function() {
            if (this.value) {
                this.style.borderColor = ''; // Restablecer el color de borde al valor predeterminado
            }
        });

        
        function excelproto(){
            var id = document.getElementById('cycle-select').value;

            if (!id) {
                document.getElementById('cycle-select').style.borderColor = 'red';
                alert("Por favor, seleccione un ciclo.");
                return; 
            }

            
            $.ajax({
                    url: '{{route('dashboard.excel_proto')}}/'+id,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'students.xlsx'; // Nombre del archivo
                        document.body.append(a);
                        a.click();
                        window.URL.revokeObjectURL(url);

                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", error);
                    }
                });

        }

        function excelcourse(){
            var id = document.getElementById('cycle-select2').value;

            if (!id) {
                document.getElementById('cycle-select2').style.borderColor = 'red';
                alert("Por favor, seleccione un ciclo.");
                return; 
            }
            
            $.ajax({
                    url: '{{route('dashboard.excel_course')}}/'+id,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'students.xlsx'; // Nombre del archivo
                        document.body.append(a);
                        a.click();
                        window.URL.revokeObjectURL(url);

                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", error);
                    }
                });

        }

        function excelgroups(){
            var id = document.getElementById('cycle-select3').value;
            if (!id) {
                document.getElementById('cycle-select3').style.borderColor = 'red';
                alert("Por favor, seleccione un ciclo.");
                return; 
            }
            
            $.ajax({
                    url: '{{route('dashboard.excel_groups')}}/'+id,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'groups.xlsx'; // Nombre del archivo
                        document.body.append(a);
                        a.click();
                        window.URL.revokeObjectURL(url);

                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", error);
                    }
                });

        }

        function excelextensions(){
            var id = document.getElementById('cycle-select4').value;
            if (!id) {
                document.getElementById('cycle-select4').style.borderColor = 'red';
                alert("Por favor, seleccione un ciclo.");
                return; 
            }
            
            $.ajax({
                    url: '{{route('dashboard.excel_extensions')}}/'+id,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'extensions.xlsx'; // Nombre del archivo
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
