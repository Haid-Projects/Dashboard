@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5">
        <div class="row justify-content-evenly">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 ">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col ">
                                <div class="text-lg font-weight-bolder text-uppercase mb-1 text-end" style="color: #0d5975">الطلبات الكلية
                                </div>
                                <div class="row no-gutters align-items-center">

                                    <div class="col">
                                        {{--                                        <div class="progress progress-sm mr-2">--}}
                                        {{--                                            <div class="progress-bar bg-info" role="progressbar"--}}
                                        {{--                                                 style="width: 50%" aria-valuenow="50" aria-valuemin="0"--}}
                                        {{--                                                 aria-valuemax="100">--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                    </div>
                                    <div class="col-auto">
                                        <div class="h2 mb-0 mr-3 font-weight-bold " style="color: #ffdf3f">{{$all_forms}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                {{--                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 ">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col ">
                                <div class="text-lg font-weight-bolder text-uppercase mb-1 text-end" style="color: #0d5975">الطلبات المقبولة
                                </div>
                                <div class="row no-gutters align-items-center">

                                    <div class="col">
                                        {{--                                        <div class="progress progress-sm mr-2">--}}
                                        {{--                                            <div class="progress-bar bg-info" role="progressbar"--}}
                                        {{--                                                 style="width: 50%" aria-valuenow="50" aria-valuemin="0"--}}
                                        {{--                                                 aria-valuemax="100">--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                    </div>
                                    <div class="col-auto">
                                        <div class="h2 mb-0 mr-3 font-weight-bold " style="color: #004b69">{{$all_forms - $rejected_forms}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                {{--                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 ">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col ">
                                <div class="text-lg font-weight-bolder text-uppercase mb-1 text-end" style="color: #0d5975">الطلبات المرفوضة
                                </div>
                                <div class="row no-gutters align-items-center">

                                    <div class="col">
{{--                                        <div class="progress progress-sm mr-2">--}}
{{--                                            <div class="progress-bar bg-info" role="progressbar"--}}
{{--                                                 style="width: 50%" aria-valuenow="50" aria-valuemin="0"--}}
{{--                                                 aria-valuemax="100">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                    <div class="col-auto">
                                        <div class="h2 mb-0 mr-3 font-weight-bold " style="color: #1f9f1c">{{$rejected_forms}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
{{--                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 ">

                <div class="card shadow mb-4">
                   {!! $pie_chart->container() !!}
                    <script src="{{ $pie_chart->cdn() }}"></script>

                    {{ $pie_chart->script() }}
                </div>
            </div>

            <div class="col-6">
                <div class="card shadow mb-4">

                    {!! $chart1->container() !!}
                    <script src="{{ $chart1->cdn() }}"></script>

                    {{ $chart1->script() }}
                </div>
            </div>

            <div class="col-6">
                <div class="card shadow mb-4">
                    {!! $donut_chart->container() !!}
                    <script src="{{ $donut_chart->cdn() }}"></script>

                    {{ $donut_chart->script() }}
                </div>
            </div>

            <div class="col-6">
                <div class="card shadow mb-4">

                    {!! $chart2->container() !!}
                    <script src="{{ $chart2->cdn() }}"></script>

                    {{ $chart2->script() }}
                </div>
            </div>
        </div>
    </div>

@endsection
