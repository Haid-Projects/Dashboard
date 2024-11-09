@extends('dashboard.sidebar')

@section('content')
    <div class="col-9 py-5 ps-5">
        <div class="row justify-content-end">

            <form class="d-flex col-4 alen" role="search">
                <input class="form-control me-2 text-secondary" name="name" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-secondary shadow-sm" type="submit">
                    <img src="/icons/filter.svg" width="10px" height="10px" />
                </button>
            </form>
        </div>
        <table class="table table-striped mt-4 " style="border: hidden">
            <thead style="border: hidden">
            <tr>
                <th class="col-2">التقييم</th>
                <th class="col-3 ">تاريخ البدء</th>
                <th class="col-2">اسم المرض</th>
                <th class="col-2">اسم المستفيد</th>
                <th class="col-1">#</th>
            </tr>
            </thead>
            <tbody >
            @foreach($forms as $form)
                <tr style="border: hidden">
                    <td class="col-2">{{$form->total_points}}</td>
                    <td class="col-3 text-light">{{$form->created_at}}</td>
                    <td class="col-2">{{date("d/m/Y",strtotime($form->illness_name))}}</td>
                    <td class="col-2" ><a href="{{route('beneficiary_reports', $form->id)}}" >{{$form->full_name}}</a></td>
                    <td class="col-1">{{$form->id}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="my-5 ">
            {{ $forms->withQueryString()->links() }}
        </div>

    </div>
@endsection
