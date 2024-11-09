@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5" id="content">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <div class="col-5 d-inline">
{{--                    <a href="/generateSpecialistsReportPdf" style="color: white" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModalDefault">--}}
{{--                        تصدير تقرير بصيغة pdf--}}
{{--                    </a>--}}
{{--                    <form action="/generate-pdf" method="POST">--}}
{{--                        @csrf--}}
{{--                        <input type="text" id="url" value="{{\Illuminate\Support\Facades\URL::current()}}" name="url" hidden>--}}
                        <button type="submit" id="screenshot" style="color: white" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModalDefault">
                            تصدير تقرير بصيغة pdf
                        </button>
{{--                    </form>--}}
                </div>
            </div>
            <div class="row">
                <h1 class="h3 mb-2 text-end text-gray-800">الاخصائيين</h1>
                <p class="mb-4 text-end">. بعض المعلومات المتعلقة حول الاخصائيين واعمالهم</p>

                <div class="col-7">
                    <h2 class="h3 mb-2 text-gray-800">تقييم الاخصائيين حسب خدمة معينة</h2>
                    <p class="mb-4">يحوي الجدول على تقييم الاخصائيين من قبل المستفيدين وذلك بشكل حسب خدمة معينة.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary text-end">التقييمات حسب الخدمة</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <form class="d-flex col-8 my-2" role="search">
                                        <input class="form-control me-2 text-secondary" name="name" type="search" placeholder="Search" aria-label="Search">
                                        <button class="btn btn-outline-secondary shadow-sm" type="submit">
                                            <img src="/icons/filter.svg" width="10px" height="10px" />
                                        </button>
                                    </form>
                                </div>
                                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>التقييم</th>
                                        <th>اسم الخدمة</th>
                                        <th>اسم الاخصائي</th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>التقييم</th>
                                        <th>اسم الخدمة</th>
                                        <th>اسم الاخصائي</th>

                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($sortByIllness as $illness)
                                        <tr>
                                            <td>{{$illness->average}}</td>
                                            <td>{{$illness->illness_name}}</td>
                                            <td>{{$illness->name}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-5">
                    <h2 class="h3 mb-2 text-gray-800">تقييم الاخصائيين حسب رأي المستفيدين</h2>
                    <p class="mb-4">يحوي الجدول على تقييم الاخصائيين من قبل المستفيدين وذلك بشكل عام.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary text-end">التقييمات</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>التقييم</th>
                                        <th>اسم الاخصائي</th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>التقييم</th>
                                        <th>اسم الاخصائي</th>

                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($specialists as $specialist)
                                        <tr>
                                            <td>{{$specialist->average}}</td>
                                            <td>{{$specialist->name}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <h2 class="h3 mb-2 text-gray-800">الحالات المنجزة لكل اخصائي</h2>
                    <p class="mb-4">يحوي الجدول على عدد الحالات المنجزة بنجاح لكل اخصائي.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary text-end">الحالات المنجزة</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>

                                        <th>عدد الحالات</th>
                                        <th>اسم الاخصائي</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>

                                        <th>عدد الحالات</th>
                                        <th>اسم الاخصائي</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($closedFormsForEachOne as $form)
                                        <tr>
                                            <td>{{$form->count}}</td>
                                            <td>{{$form->name}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <h2 class="h3 mb-2 text-gray-800">الحالات السارية لكل اخصائي</h2>
                    <p class="mb-4">يحوي الجدول على عدد الحالات السارية التي يقوم كل اخصائي بمعالجتها في الوقت الحالي.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary text-end">الحالات السارية</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>عدد الحالات</th>
                                        <th>اسم الاخصائي</th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>عدد الحالات</th>
                                        <th>اسم الاخصائي</th>

                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($openedFormsForEachOne as $form)
                                        <tr>
                                            <td>{{$form->count}}</td>
                                            <td>{{$form->name}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-5 ">

                    <div class="card shadow mb-4">
                        {!! $pie_chart->container() !!}
                        <script src="{{ $pie_chart->cdn() }}"></script>

                        {{ $pie_chart->script() }}
                    </div>
                </div>

                <div class="col-7">
                    <div class="card shadow mb-4">

                        {!! $chart1->container() !!}
                        <script src="{{ $chart1->cdn() }}"></script>

                        {{ $chart1->script() }}
                    </div>
                </div>

                <div class="col-5">
                    <div class="card shadow mb-4">
                        {!! $donut_chart->container() !!}
                        <script src="{{ $donut_chart->cdn() }}"></script>

                        {{ $donut_chart->script() }}
                    </div>
                </div>

                <div class="col-7">
                    <div class="card shadow mb-4">

                        {!! $chart2->container() !!}
                        <script src="{{ $chart2->cdn() }}"></script>

                        {{ $chart2->script() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#screenshot').on('click', function() {
                html2canvas(document.querySelector("#content")).then(canvas => {
                    // Convert the canvas to a data URL
                    var imgData = canvas.toDataURL('image/png');

                    // Create a new jsPDF instance with orientation depending on the image aspect ratio
                    const { jsPDF } = window.jspdf;

                    // Get the dimensions of the canvas (which represents the image)
                    var imgWidth = canvas.width;
                    var imgHeight = canvas.height;

                    // Determine PDF dimensions to maintain the aspect ratio
                    var pdfWidth = imgWidth * 0.264583;  // Convert pixel to mm (1px = 0.264583mm)
                    var pdfHeight = imgHeight * 0.264583;

                    // Create a new PDF document with the calculated dimensions
                    var pdf = new jsPDF({
                        orientation: pdfWidth > pdfHeight ? 'landscape' : 'portrait',
                        unit: 'mm',
                        format: [pdfWidth, pdfHeight]
                    });

                    // Add the image to the PDF with no margins, filling the entire page
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

                    // Save the PDF
                    pdf.save('screenshot.pdf');
                });
            });
        });
    </script>

@endsection
