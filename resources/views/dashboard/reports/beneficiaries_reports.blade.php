@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5" id="content">
        <div class="container-fluid">
            <div class="row justify-content-between mb-4">
                <div class="col-5 d-inline">
                    <button type="submit" id="screenshot" style="color: white" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModalDefault">
                        تصدير تقرير بصيغة pdf
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="card shadow mb-4">

                        {!! $chart->container() !!}
                        <script src="{{ $chart->cdn() }}"></script>

                        {{ $chart->script() }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4"></div>
                <div class="col-8">
                    <h1 class="my-3 text-end">معلومات المستفيد</h1>
                    <div class="card shadow mb-4 py-3">
                        <div class="row justify-content-between my-2">
                            <h4 class="px-5 col-6 text-primary">{{$beneficiary->full_name}}</h4>
                            <h4 class="px-5 col-6 text-end"> الاسم الكامل </h4>
                        </div>
                        <hr class="w-75 mx-auto">

                        <div class="row justify-content-between my-2">
                            <h4 class="px-5 col-6 text-primary">{{$beneficiary->birthdate}}</h4>
                            <h4 class="px-5 col-6 text-end">العمر </h4>
                        </div>
                        <hr class="w-75 mx-auto">

                        <div class="row justify-content-between my-2">
                            <h4 class="px-5 col-6 text-primary">{{$beneficiary->gender}}</h4>
                            <h4 class="px-5 col-6 text-end">الجنس </h4>
                        </div>
                        <hr class="w-75 mx-auto">

                        <div class="row justify-content-between my-2">
                            <h4 class="px-5 col-6 text-primary">{{$beneficiary->marital_status}}</h4>
                            <h4 class="px-5 col-6 text-end">الحالة الزوجية </h4>
                        </div>
                        <hr class="w-75 mx-auto">

                        <div class="row justify-content-between my-2">
                            <h4 class="px-5 col-6 text-primary">{{$beneficiary_form->specialist->name}}</h4>
                            <h4 class="px-5 col-6 text-end">الاخصائي المسؤول </h4>
                        </div>
                        <hr class="w-75 mx-auto">
                        <div class="row justify-content-between my-2">
                            <h4 class="px-5 col-6 text-primary">{{$p_sessions}}</h4>
                            <h4 class="px-5 col-6 text-end">عدد جلسات الحضور </h4>
                        </div>
                        <hr class="w-75 mx-auto">

                        <div class="row justify-content-between my-2">
                            @if($a_sessions >= ($a_sessions + $p_sessions)/3)
                            <h4 class="px-5 col-6 text-danger">{{$a_sessions}}</h4>
                            @else
                                <h4 class="px-5 col-6 text-primary">{{$a_sessions}}</h4>
                            @endif
                            <h4 class="px-5 col-6 text-end">عدد جلسات الغياب </h4>
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
