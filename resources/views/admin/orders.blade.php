@extends('layouts.admin')

@section('content')
        <div class="row justify-content-center">
            <div class="col-12">
                @include('admin.cards.orders')

                <div class="mt-4 d-flex justify-content-center">{{ $orders->links() }}</div>
            </div>
        </div>
@endsection
