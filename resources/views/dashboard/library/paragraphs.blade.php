@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5">
        <div class="row">
            <div style="background-color: #f0f9ff;border-radius: 20px;" class=" text-center p-5">
                <h1 class="mb-5" style="color: #004b69">الفقرات</h1>
                @foreach($paragraphs as $paragraph)
                    <div class="">
                        <span>
                            <a href="/deleteParagraph/{{$paragraph->id}}">
                                <img src="/icons/trash.svg"  width="17px" height="17px" class="mx-4 hovered" />
                            </a>
                        </span>
                        <span data-bs-toggle="modal" data-bs-target="#updateparagraph{{$paragraph->id}}">
                            <img src="/icons/edit.svg"  width="17px" height="17px" class="mx-4 hovered" />
                        </span>
                        <a href="{{route('medias', $paragraph->id)}}" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)" class="ms-2 btn my-1 px-5 fw-bold d-inline-block mx-auto w-50">
                            {{$paragraph->title}}
                        </a>
                    </div>

                    <div class="modal fade" id="updateparagraph{{$paragraph->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog ">
                            <div class="modal-content me-0" >
                                <div class="modal-header" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">
                                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">تعديل فقرة</h1>
                                    {{--                    <button type="button" class="btn-close " style="color: white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                                </div>
                                <form action="{{route('editParagraph', $paragraph->id)}}" method="post" >
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="row">
                                            <div class="form-group text-end col-8">
                                                <input style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold" value="{{$paragraph->title}}" id="title" placeholder=" اسم الفقرة" name="title" required>
                                            </div>
                                            <div class="form-group text-end col-4">
                                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="title">اسم الفقرة</label>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="form-group text-end col-8">
                                                <textarea style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold" id="text" placeholder=" اسم الفقرة" name="text" required>{{$paragraph->text}}</textarea>
                                            </div>
                                            <div class="form-group text-end col-4">
                                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="text">اسم الفقرة</label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">الغاء</button>
                                        <button type="submit" class="btn px-5" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">تعديل</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                @endforeach
                <div class="mt-5 row">
                    <div class="col-2"></div>
                    <a style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)" class="col-6 btn mt-5 px-5 d-inline-block mx-auto w-50" data-bs-toggle="modal" data-bs-target="#exampleModalDefault">
                        <img src="/icons/plus.svg" width="20px" height="20px" class="mx-4 " />
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalDefault" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content me-0" >
                <div class="modal-header" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">
                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">انشاء فقرة</h1>
                    {{--                    <button type="button" class="btn-close " style="color: white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                </div>
                <form action="{{route('createParagraph', $main_title_id)}}" method="post" >
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="form-group text-end col-8">
                                <input style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold" id="title" placeholder=" اسم الفقرة" name="title" required>
                            </div>
                            <div class="form-group text-end col-4">
                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="title">اسم الفقرة</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group text-end col-8">
                                <textarea style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold" id="text" placeholder=" نص الفقرة" name="text" rows="9" required></textarea>
                            </div>
                            <div class="form-group text-end col-4">
                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="text">نص الفقرة</label>
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
