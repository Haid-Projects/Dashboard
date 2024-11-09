@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5">
        <div class="row justify-content-between">
            <div class="col-6 d-inline">
            </div>
            <div class="col-5 d-inline">
                <h1 class="text-end">الاشعارات</h1>
            </div>
        </div>
        <table class="table table-striped mt-4 " style="border: hidden">
            <thead style="border: hidden">
            <tr>
                <th class="text-center" scope="col ">نص الاشعار</th>
                <th scope="col">النوع</th>
            </tr>
            </thead>
            <tbody >
            @foreach($notifications as $notification)
                <tr style="border: hidden">
                    <td class="col-8 text-center">{{$notification->data["message"]}}</td>
                    <td class="col-2">طلب اعادة نظر</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
