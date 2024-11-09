@extends('dashboard.sidebar')
@section('content')
    <div class="col-9 py-5 ps-5">

        <div class="container">
            <div class="main-body">
                <div class="row gutters-sm">

                    <div class="col-md-8">
    {{--                    @if(session()->has('success'))--}}
    {{--                        <div class="alert alert-success mb-2" style="background-color: #33ce33">--}}
    {{--                            {{ session('success') }}--}}
    {{--                        </div>--}}
    {{--                    @endif--}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Full Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $form->full_name }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Phone</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        (239) 816-9029
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        address
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Gender</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        male
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Rate</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ $form->rate }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="accordion">

                            <a class="btn btn-outline-dark mb-4" data-toggle="collapse" href="#collapseOne">
                                <b>Schedules</b> <i class="fas fa-calendar fa-1x fa-fw mr-2 "></i>
                            </a>
                            <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                <div class="row gutters-sm">
                                    @foreach($form->sessions as $session)
                                        <div class="col-sm-6 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <h6 class="mb-0">Date</h6>
                                                        </div>
                                                        <div class="col-sm-4 text-secondary">
                                                            {{ $session->date }}
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <h6 class="mb-0">Time</h6>
                                                        </div>
                                                        <div class="col-sm-4 text-secondary">
                                                            {{ $session->time }}
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <h6 class="mb-0">Name</h6>
                                                        </div>
                                                        <div class="col-sm-4 text-secondary">
                                                            {{ $session->name }}
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <h6 class="mb-0">Specialist</h6>
                                                        </div>
                                                        <div class="col-sm-4 text-secondary">
                                                            {{ $session->specialist_id }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
