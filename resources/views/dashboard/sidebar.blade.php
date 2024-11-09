<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Juzour</title>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/all.min.css">
        <link rel="stylesheet" href="/assets/css/sidebars.css">
        <link href="/assets/css/sb-admin-2.min.css" rel="stylesheet">
        <link href="/assets/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/assets/font-awesome/css/font-awesome.min.css" />

        <style>
            .hovered:hover{
                opacity: 0.8;
            }
            body {
                font-family: 'DejaVu Sans', sans-serif;
            }
        </style>
        <script src="/assets/js/jquery-3.6.0.min.js"></script>
        <script src="/assets/js/html2canvas.min.js"></script>
        <script src="/assets/js/jspdf.umd.min.js"></script>

    </head>
    <body class="" style="background-color: #f6fdff">
        <div class="row">

            @yield('content')

            <div class="col-3 p-3 ">
                <div class="card text-end p-4 shadow-sm position-fixed" style="border-radius: 20px;border: hidden;">
                    <h3 class="mb-4 mx-1 fw-bold">مؤسسة جذور</h3>
                    <ul class="nav nav-pills flex-column mx-4">
                        <li class="nav-item py-1">
                            @if(\Illuminate\Support\Facades\URL::current() === "https:://jozour.online/main")
                            <a href="{{route('main')}}" class="nav-link px-3  fw-bold" style="background-color: #12779f;color: white;border-radius: 25px" aria-current="page">
{{--                                <svg class="bi pe-none me-2" width="16" height="16"></svg>--}}
                                الصفحة الرئيسية
                                <i class="fa fa-home ms-2"></i>
                            </a>
                                @else
                            <a href="{{route('main')}}" class="nav-link text-dark px-3 fw-bold" aria-current="page">
{{--                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>--}}
                                الصفحة الرئيسية
                                <i  class="fa fa-home ms-2"></i>

                            </a>
                                @endif

                        </li>
                        <li class="nav-item active py-1">

                            @if(\Illuminate\Support\Facades\URL::current() === "https:://jozour.online/state_managers" || \Illuminate\Support\Facades\URL::current() === "https:://jozour.online/specialists")
                                <a  class="nav-link text-light fw-bold" style="background-color: #12779f;color: white;border-radius: 25px;cursor: pointer" data-bs-toggle="collapse" data-bs-target="#employees" aria-expanded="true">
                                    الموظفين
                                    <i class="fa fa-users ms-2"></i>

                                </a>
                            @else
                                <a  class="nav-link text-dark fw-bold" style="cursor: pointer"  data-bs-toggle="collapse" data-bs-target="#employees" aria-expanded="true">
                                    الموظفين
                                    <i class="fa fa-users ms-2"></i>

                                </a>

                            @endif
                            <div class="collapse" id="employees">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small px-3">
                                    <li>
                                        <a href="{{route('state_managers')}}" class="nav-link text-dark fw-bold" aria-current="page">
                                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                             مدراء الحالة

                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('specialists')}}" class="nav-link text-dark fw-bold" aria-current="page">
                                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                             الاخصائيين
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item py-1">
                            @if(\Illuminate\Support\Facades\URL::current() === "https:://jozour.online/services")
                                <a href="/services" class="nav-link text-light fw-bold" style="background-color: #12779f;color: white;border-radius: 25px" aria-current="page">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                    البرامج
                                    <i class="fa fa-puzzle-piece ms-2"></i>

                                </a>
                            @else
                                <a href="/services" class="nav-link text-dark fw-bold" aria-current="page">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                    البرامج
                                    <i class="fa fa-puzzle-piece ms-2"></i>

                                </a>
                            @endif

                        </li>
                        <li class="nav-item py-1">
                            @if(\Illuminate\Support\Facades\URL::current() === "https:://jozour.online/forms")
                                <a href="/forms" class="nav-link text-light fw-bold" style="background-color: #12779f;color: white;border-radius: 25px" aria-current="page">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                    الاستمارات
                                    <i class="fa fa-files-o ms-2"></i>

                                </a>
                            @else
                                <a href="/forms" class="nav-link text-dark fw-bold" aria-current="page">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                    الاستمارات
                                    <i class="fa fa-files-o ms-2"></i>

                                </a>
                            @endif

                        </li>

                        <li class="nav-item active py-1">

                            @if(\Illuminate\Support\Facades\URL::current() === "https:://jozour.online/state_manager_reports" || \Illuminate\Support\Facades\URL::current() === "https:://jozour.online/specialist_reports" || \Illuminate\Support\Facades\URL::current() === "https:://jozour.online/beneficiary_reports")
                                <a  class="nav-link text-light fw-bold" style="background-color: #12779f;color: white;border-radius: 25px;cursor: pointer" data-bs-toggle="collapse" data-bs-target="#reports" aria-expanded="true">
                                    التقارير
                                    <i class="fa fa-file-text ms-2"></i>

                                </a>
                            @else
                                <a  class="nav-link text-dark fw-bold" style="cursor: pointer"  data-bs-toggle="collapse" data-bs-target="#reports" aria-expanded="true">
                                    التقارير
                                    <i class="fa fa-file-text ms-2"></i>

                                </a>

                            @endif
                            <div class="collapse" id="reports">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small px-3">
                                    <li>
                                        <a href="{{route('state_manager_reports')}}" class="nav-link text-dark fw-bold" aria-current="page">
                                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                            تقارير مدراء الحالة
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('specialist_reports')}}" class="nav-link text-dark fw-bold" aria-current="page">
                                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                            تقارير الاخصائيين
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('beneficiary_reports',1)}}" class="nav-link text-dark fw-bold" aria-current="page">
                                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                            تقارير المستفيدين
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item py-1">
                            <a href="{{route('subjects')}}" class="nav-link text-dark fw-bold" aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                المكتبة
                                <i class="fa fa-book ms-2"></i>

                            </a>
                        </li>
                        <li class="nav-item py-1">
                            @if(\Illuminate\Support\Facades\URL::current() === "https:://jozour.online/notifications")
                                <a href="/notifications" class="nav-link text-light fw-bold" style="background-color: #12779f;color: white;border-radius: 25px" aria-current="page">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                    الاشعارات
                                    <i class="fa fa-bell-o ms-2"></i>

                                </a>
                            @else
                                <a href="/notifications" class="nav-link text-dark fw-bold" aria-current="page">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                    الاشعارات
                                    <i class="fa fa-bell-o ms-2"></i>

                                </a>
                            @endif

                        </li>
                        @auth('admin')
                        <li class="nav-item py-1">
                            <a href="{{route('logout')}}" class="nav-link text-dark fw-bold" aria-current="page">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                                تسجيل الخروج
                                <i class="fa fa-sign-out ms-2"></i>

                            </a>
                        </li>
                        @endauth
                    </ul>
                    <img src="/icons/juzour.png" class="w-50 mt-3 mx-auto"/>
                </div>
            </div>
        </div>


    <script src="/assets/js/bootstrap.bundle.min.js"></script>
{{--    <script src="/assets/js/cheatsheet.js"></script>--}}
    <script src="/assets/js/sidebars.js"></script>


    <!-- Bootstrap core JavaScript-->
    <script src="/assets/js/jquery.min.js"></script>

        <!-- Page level plugins -->
        <script src="/assets/js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="/assets/js/chart-area-demo.js"></script>
        <script src="/assets/js/chart-pie-demo.js"></script>
        <script src="/assets/js/chart-bar-demo.js"></script>

          <!-- Page level plugins -->
    <script src="/assets/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="/assets/datatables/datatables-demo.js"></script>

{{--        <script src="/assets/js/apexcharts.js"></script>--}}

    </body>
</html>
