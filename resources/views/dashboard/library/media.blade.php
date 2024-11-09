@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5">
        <div class="row">
            <div style="background-color: #f0f9ff;border-radius: 20px;" class=" text-center p-5">
                <h1 class="mb-5" style="color: #004b69">الوسائط</h1>
                @foreach($medias as $media)
                    <div class="">
                        <span>
                            <a href="/deleteMedia/{{$media->id}}">
                                <img src="/icons/trash.svg"  width="17px" height="17px" class="mx-4 hovered" />
                            </a>
                        </span>
                        <span data-bs-toggle="modal" data-bs-target="#updatemedia{{$media->id}}">
                            <img src="/icons/edit.svg"  width="17px" height="17px" class="mx-4 hovered" />
                        </span>
                        <a href="{{route('medias', $media->id)}}" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)" class="ms-2 btn my-1 px-5 fw-bold d-inline-block mx-auto w-50">
                            {{$media->label??"عنوان الوسائط"}}
                        </a>
                    </div>

                    <div class="modal fade" id="updatemedia{{$media->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog ">
                            <div class="modal-content me-0" >
                                <div class="modal-header" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">
                                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">تعديل الوسائط</h1>
                                    {{--                    <button type="button" class="btn-close " style="color: white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                                </div>
                                <form action="{{route('editMedia', $media->id)}}" method="post" >
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="row">
                                            <div class="form-group text-end col-8">
                                                <input style="background-color: #f6fdff" type="url" class="form-control text-end text-dark fw-bold" value="{{$media->url}}" id="url" placeholder=" رابط الوسائط" name="url" required>
                                            </div>
                                            <div class="form-group text-end col-4">
                                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="url">رابط الوسائط</label>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="form-group text-end col-8">
                                                <select style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold" value="{{$media->type}}" name="type" id="type">
                                                    @if($media->type === "photo")
                                                    <option  selected value="photo">photo</option>
                                                    <option value="video">video</option>
                                                    @else
                                                        <option  value="photo">photo</option>
                                                        <option selected value="video">video</option>
                                                    @endif
                                                </select>
{{--                                                <input style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold" value="{{$media->type}}" id="url" placeholder=" رابط الوسائط" name="url" required>--}}
                                            </div>
                                            <div class="form-group text-end col-4">
                                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="type">نوع الوسائط</label>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="form-group text-end col-8">
                                                <input style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold" value="{{$media->label}}" id="label" placeholder=" عنوان الوسائط" name="label" required>
                                            </div>
                                            <div class="form-group text-end col-4">
                                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="label">عنوان الوسائط</label>
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
                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">انشاء وسائط</h1>
                    {{--                    <button type="button" class="btn-close " style="color: white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                </div>
                <form action="{{route('createMedia', $paragraph_id)}}" method="post" >
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="form-group text-end col-8">
                                <input style="background-color: #f6fdff" type="url" class="form-control text-end text-dark fw-bold" id="url" placeholder=" رابط الوسائط" name="url" required>
                            </div>
                            <div class="form-group text-end col-4">
                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="url">رابط الوسائط</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group text-end col-8">
                                <select style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold"  name="type" id="type">
                                    <option value="photo">photo</option>
                                    <option value="video">video</option>
                                </select>
                                {{--                                                <input style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold" value="{{$media->type}}" id="url" placeholder=" رابط الوسائط" name="url" required>--}}
                            </div>
                            <div class="form-group text-end col-4">
                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="type">نوع الوسائط</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group text-end col-8">
                                <input style="background-color: #f6fdff" type="text" class="form-control text-end text-dark fw-bold" id="label" placeholder=" عنوان الوسائط" name="label" required>
                            </div>
                            <div class="form-group text-end col-4">
                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="label">اسم الوسائط</label>
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
