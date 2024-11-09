@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5">
        <div class="row">

            <div style=" background-image: linear-gradient(to bottom right,#0194cf, #014b69); border-top-right-radius: 20px; border-top-left-radius: 20px;border-bottom-right-radius: 20px; border-bottom-left-radius: 20px; " class="col-8 mx-auto  text-center text-light p-5">
                <h1 class="mb-5" style="color: #f0f9ff;">{{$dimension->name}}</h1>
                @foreach($dimension->questions as $question)
                    <div class="">
                         <span>
                                <a href="/deleteQuestion/{{$question->id}}">
                                    <img src="/icons/trash.svg"  width="17px" height="17px" class="mx-4 hovered" />
                                </a>
                         </span>
                         <span data-bs-toggle="modal" data-bs-target="#updateQuestion{{$question->id}}">
                            <img src="/icons/edit1.svg"  width="19px" height="19px" class="mx-4 hovered" />
                         </span>

                        <span  style="color: #004b69;background-color: #f0f9ff;" class="btn my-1 px-5 fw-bold d-inline-block mx-auto w-75">
                               {{$question->label}}
                        </span>
                        <br>
                    </div>

                    <div class="modal fade" id="updateQuestion{{$question->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog ">
                            <div class="modal-content me-0" >
                                <div class="modal-header" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">
                                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">تعديل سؤال</h1>
                                    {{--                    <button type="button" class="btn-close " style="color: white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                                </div>
                                <form action="{{route('editQuestion',$question->id)}}" method="post" >
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="row">
                                            <div class="form-group text-end col-8">
                                                <input style="background-color: #f6fdff" type="text" class="form-control text-end" id="label" value="{{$question->label}}" placeholder="نص السؤال" name="label" required>
                                            </div>
                                            <div class="form-group text-end col-4">
                                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="label">نص السؤال</label>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="form-group text-end col-6">
                                                <label style="color: #004b69" class="text-end my-2 fw-bold" for="rank">أدخل ترتيب السؤال</label>
                                                <input style="background-color: #f6fdff" type="number" class="form-control text-end" id="rank" value="{{$question->rank}}" placeholder="أدخل ترتيب السؤال" name="rank" required>
                                            </div>
                                            <div class="form-group text-end col-6">
                                                <label style="color: #004b69" class="text-end my-2 fw-bold" for="points">عدد النقاط للسؤال </label>
                                                <input style="background-color: #f6fdff" type="number" class="form-control text-end" id="points" value="{{$question->points}}" placeholder="أدخل عدد النقاط للسؤال " name="points" required>
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
                      <div class="col-3"></div>
                    <a style="color: #004b69;background-color: #f0f9ff;" class="col-9 btn mt-5 d-inline-block mx-auto " data-bs-toggle="modal" data-bs-target="#exampleModalDefault">
                        <img src="/icons/plus1.svg" width="20px" height="20px" class="mx-4 " />
                    </a>
                </div>

            </div>

        </div>
  </div>

    <div class="modal fade" id="exampleModalDefault" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content me-0" >
                <div class="modal-header" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">
                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">انشاء سؤال</h1>
                    {{--                    <button type="button" class="btn-close " style="color: white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                </div>
                <form action="{{route('createQuestion',$dimension->id)}}" method="post" >
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="form-group text-end col-8">
                                <input style="background-color: #f6fdff" type="text" class="form-control text-end" id="label" placeholder="نص السؤال" name="label" required>
                            </div>
                            <div class="form-group text-end col-4">
                                <label style="color: #004b69;font-size: 2.3ex" class="text-end my-1 fw-bold" for="label">نص السؤال</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group text-end col-6">
                                <label style="color: #004b69" class="text-end my-2 fw-bold" for="rank">أدخل ترتيب السؤال</label>
                                <input style="background-color: #f6fdff" type="number" class="form-control text-end" id="rank" placeholder="أدخل ترتيب السؤال" name="rank" required>
                            </div>
                            <div class="form-group text-end col-6">
                                <label style="color: #004b69" class="text-end my-2 fw-bold" for="points">عدد النقاط للسؤال </label>
                                <input style="background-color: #f6fdff" type="number" class="form-control text-end" id="points" placeholder="أدخل عدد النقاط للسؤال " name="points" required>
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
