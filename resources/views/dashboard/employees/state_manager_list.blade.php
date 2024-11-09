@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5">
        <div class="row justify-content-between">
            <div class="col-5 d-inline">
                <button type="button" style="color: white" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModalDefault">
                    <img src="/icons/plus.svg" width="15px" height="17px" class="me-1" />
                    اضافة مدير حالة
                </button>
                {{--                <button type="button" style="color: white;background-color: #12779f" class="btn ms-2 px-3">--}}
                {{--                    <img src="/icons/sort.svg" width="15px" height="17px" class="me-1" />--}}
                {{--                    مدير حالة--}}
                {{--                </button>--}}
            </div>

            <form class="d-flex col-4" role="search">
                <input class="form-control me-2 text-secondary" name="name" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-secondary shadow-sm" type="submit">
                    <img src="/icons/filter.svg" width="10px" height="10px" />
                </button>
            </form>
        </div>

        <table class="table table-striped mt-4 " style="border: hidden">
            <thead style="border: hidden">
            <tr>
                <th scope="col">حذف</th>
                <th scope="col">تعديل</th>
{{--                <th scope="col">رقم الهاتف</th>--}}
                <th scope="col">اسم المستخدم</th>
                <th scope="col">الاسم</th>
                <th scope="col">#</th>
            </tr>
            </thead>
            <tbody >
            @foreach($state_managers as $state_manager)
                <tr style="border: hidden">
                    <td>
                        <a href="/deleteStateManager/{{$state_manager->id}}">
                            <img width="25px" height="25px"  src="/icons/trash.svg" />
                        </a>
                    </td>
                    <td data-bs-toggle="modal" data-bs-target="#updateStateManager{{$state_manager->id}}">
                        <img width="25px" height="25px"  src="/icons/edit.svg" />
                    </td>
                    <td>{{$state_manager->username}}</td>
                    <td>{{$state_manager->name}}</td>
                    <th scope="row">{{$state_manager->id}}</th>
                </tr>

                <div class="modal fade" id="updateStateManager{{$state_manager->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog ">
                        <div class="modal-content me-0" >
                            <div class="modal-header" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">
                                <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">تعديل معلومات مدير حالة</h1>
                            </div>
                            <form action="{{route('editStateManager', $state_manager->id)}}" method="post" >
                                @csrf
                                <div class="modal-body p-4">
                                    <div class="row">
                                        <div class="form-group text-end col-6">
                                            <label style="color: #004b69" class="text-end my-2 fw-bold" for="name">الاسم الثلاثي</label>
                                            <input style="background-color: #f6fdff" type="text" class="form-control text-end" value="{{$state_manager->name}}" id="name" placeholder="أدخل اسم المستخدم" name="name" required>
                                        </div>


{{--                                        <div class="form-group text-end col-6">--}}
{{--                                            <label style="color: #004b69" class="text-end my-2 fw-bold" for="password">كلمة المرور</label>--}}
{{--                                            <input style="background-color: #f6fdff" type="password" class="form-control text-end" value="{{$state_manager->password}}" id="password" placeholder="أدخل كلمة المرور" name="password" required>--}}
{{--                                        </div>--}}
                                        <div class="form-group text-end col-6">
                                            <label style="color: #004b69" class="text-end my-2 fw-bold" for="username">اسم المستخدم</label>
                                            <input style="background-color: #f6fdff" type="text" class="form-control text-end" value="{{$state_manager->username}}" id="username" placeholder="أدخل اسم المستخدم" name="username" required>
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

            </tbody>
        </table>
        <div class="my-5 ">
            {{ $state_managers->withQueryString()->links() }}

        </div>
    </div>
    <div class="modal fade" id="exampleModalDefault" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content me-0" >
                <div class="modal-header" style="color: white;background-image: linear-gradient(to bottom left,#0194cf, #014b69)">
                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">انشاء مدير حالة</h1>
{{--                    <button type="button" class="btn-close " style="color: white" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                </div>
                <form action="{{route('createStateManager')}}" method="post" >
                    @csrf
                <div class="modal-body p-4">
                    <div class="row">

                        <div class="form-group text-end col-6">
                            <label style="color: #004b69" class="text-end my-2 fw-bold" for="password">كلمة المرور</label>
                            <input style="background-color: #f6fdff" type="password" class="form-control text-end" id="password" placeholder="أدخل كلمة المرور" name="password" required>
                        </div>
                        <div class="form-group text-end col-6">
                            <label style="color: #004b69" class="text-end my-2 fw-bold" for="name">الاسم الثلاثي</label>
                            <input style="background-color: #f6fdff" type="text" class="form-control text-end" id="name" placeholder="أدخل اسم المستخدم" name="name" required>
                        </div>



                        <div class="form-group text-end col-6">
                            <label style="color: #004b69" class="text-end my-2 fw-bold" for="phone">رقم الهاتف</label>
                            <input style="background-color: #f6fdff" type="text" class="form-control text-end" id="phone" placeholder="أدخل كلمة المرور" name="phone" required>
                        </div>
                        <div class="form-group text-end col-6">
                            <label style="color: #004b69" class="text-end my-2 fw-bold" for="username">اسم المستخدم</label>
                            <input style="background-color: #f6fdff" type="text" class="form-control text-end" id="username" placeholder="أدخل اسم المستخدم" name="username" required>
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
