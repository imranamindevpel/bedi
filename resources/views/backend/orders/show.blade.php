@extends('backend.layouts.master')
@section('content')
<style>
td {
    padding: 2px 10px;
}

.sorting {
    text-transform: uppercase;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @can('order-create')
                            <a type="button" class="btn btn-sm btn-info float-right" href="{{ url('/') }}">Create New
                                Order</a>
                            @endcan
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (json_decode($order->detail) as $item)
                                            <tr>
                                                <td><img src="{{ $item->attributes->image }}" alt="Product Image"
                                                        width="100" height="100"></td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ $item->price }}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2">
                                                    <a class="btn btn-primary" href="{{$order->receipt_url}}">
                                                        <small>Invoice</small>
                                                    </a>
                                                </td>
                                                <td><b>Total Price: </b></td>
                                                <td><b>{{ $order->total }}/-</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
@endsection