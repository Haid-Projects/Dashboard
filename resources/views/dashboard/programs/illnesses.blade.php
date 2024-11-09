@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5">
        <div class="row">
            <div style="background-color: #f0f9ff;border-top-left-radius: 20px;border-bottom-left-radius: 20px" class="col-6 text-center p-5">
                <h1 class="mb-5" style="color: #004b69">الخدمات</h1>
                @foreach($illnesses as $illness)
                    <div class="">
                        <span>
                            <a href="/deleteIllness/{{$illness->id}}">
                                <img src="/icons/trash.svg"  width="17px" height="17px" class="mx-4 hovered" />
                            </a>                        </span>
                        <span data-bs-toggle="modal" data-bs-target="#updateIllness{{$illness->id}}">
                            <img src="/icons/edit.svg"  width="17px" height="17px" class="mx-4 hovered" />
                        </span>

                        <a href="{{route('dimensions', $illness->id)}}" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)" class="btn my-1 px-5 fw-bold d-inline-block mx-auto w-50">
                            {{$illness->name}}
                        </a>
                    </div>

                    <div class="modal fade" id="updateIllness{{$illness->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog ">
                            <div class="modal-content me-0" >
                                <div class="modal-header" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">
                                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">تعديل خدمة</h1>
                                    {{--                    <button type="button" class="btn-close " style="color: white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                                </div>
                                <form action="{{route('editIllness',$illness->id)}}" method="post" >
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="row">
                                            <div class="form-group text-end col-8">
                                                <input style="background-color: #f6fdff" type="text" class="form-control text-end" value="{{$illness->name}}" id="name" placeholder=" اسم الخدمة" name="name" required>
                                            </div>
                                            <div class="form-group text-end col-4">
                                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="name">اسم الخدمة</label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">الغاء</button>
                                        <button type="submit" class="btn px-5" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">إنشاء</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                @endforeach
                <div class="mt-5">
                    <a style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)" class="btn mt-5 px-5 d-inline-block mx-auto w-50" data-bs-toggle="modal" data-bs-target="#exampleModalDefault">
                        <img src="/icons/plus.svg" width="20px" height="20px" class="mx-4 " />
                    </a>
                </div>

            </div>
            <div  style=" background-image: linear-gradient(to bottom right,#0194cf, #014b69); border-top-right-radius: 20px;border-bottom-right-radius: 20px " class="col-6  text-center text-light p-5">
                <h1 class="mt-5 pt-5" style="font-size: 50px">{{$service_name}}</h1>
                {{-- <p class="mx-2 mt-3">
                    سنعمل جنبا الى جنب لتحسين نوعية الحياة وتمكين الجميع من تحقيق إمكاناتهم.
                </p> --}}
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalDefault" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content me-0" >
                <div class="modal-header" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">
                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">انشاء خدمة</h1>
                    {{--                    <button type="button" class="btn-close " style="color: white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                </div>
                <form action="{{route('createIllness',$service_id)}}" method="post" >
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="form-group text-end col-8">
                                <input style="background-color: #f6fdff" type="text" class="form-control text-end" id="name" placeholder=" اسم الخدمة" name="name" required>
                            </div>
                            <div class="form-group text-end col-4">
                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="name">اسم الخدمة</label>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn px-5" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">إنشاء</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
