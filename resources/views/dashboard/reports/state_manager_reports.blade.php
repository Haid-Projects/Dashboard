@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5" id="content">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <div class="col-5 d-inline">
                    <button type="submit" id="screenshot" style="color: white" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModalDefault">
                        تصدير تقرير بصيغة pdf
                    </button>
                </div>
            </div>
            <div class="row">
                <h1 class="h3 mb-2 text-end text-gray-800">مدراء الحالة</h1>
                <p class="mb-4 text-end">. بعض المعلومات المتعلقة حول مدراء الحالة واعمالهم</p>

                <div class="col-6">
                    <h2 class="h3 mb-2 text-gray-800">عدد الاستمارات التي تم رفضها قبل كل مدير حالة</h2>
                    <p class="mb-4">يحوي الجدول على عدد الاستمارات المرفوضة من قبل كل مدير حالة وذلك لمعرفة كفاءة كل واحد منهم .</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary text-end">الاستمارات المرفوضة لكل موظف</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>عدد الاستمارات</th>
                                        <th>اسم مدير الحالة</th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>عدد الاستمارات</th>
                                        <th>اسم مدير الحالة</th>

                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($rejected_forms as $form)
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
                    <h2 class="h3 mb-2 text-gray-800">عدد الاستمارات التي تم قبولها قبل كل مدير حالة</h2>
                    <p class="mb-4">يحوي الجدول على عدد الاستمارات المقبولة من قبل كل مدير حالة وذلك لمعرفة كفاءة كل واحد منهم .</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary text-end">الاستمارات المقبولة لكل موظف</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>عدد الاستمارات</th>
                                        <th>اسم مدير الحالة</th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>عدد الاستمارات</th>
                                        <th>اسم مدير الحالة</th>

                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($accepted_forms as $form)
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

                <div class="col-6"></div>
                <div class="col-6 mt-2">
                    <h2 class="h3 mb-2 text-gray-800">عدد الفعاليات المنشأة من قبل كل مدير حالة</h2>
                    <p class="mb-4">يحوي الجدول على عدد الفعاليات المنشأة من قبل كل مدير حالة وذلك لمعرفة نشاط وأثر كل واحد .</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary text-end">الفعاليات لكل موظف</h6>
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
                                        <th>عدد الفعاليات</th>
                                        <th>اسم مدير الحالة</th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>عدد الفعاليات</th>
                                        <th>اسم مدير الحالة</th>

                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($events_created as $event)
                                        <tr>
                                            <td>{{$event->count}}</td>
                                            <td>{{$event->name}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
