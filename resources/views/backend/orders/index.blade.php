@extends('backend.layouts.master')
@section('content')
<style>
    td{
        padding: 2px 10px;
    }
    .sorting{
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
              <a type="button" class="btn btn-sm btn-info float-right" href="{{ url('/') }}">Create New Order</a>
              @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Invoices</th>
                  <th>Status</th>
                  <th>Total</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($orders as $key => $order)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>
                      <a class="btn btn-primary" href="{{$order->receipt_url}}">
                          <small>Invoice</small>
                      </a>
                    </td>
                    <td>
                      @if ($order->status == 0)
                          <span class="bg-red p-1">Pending</span>
                      @elseif ($order->status == 1)
                          <span class="bg-green p-1">Delivered</span>
                      @endif
                    </td>
                    <td>{{ $order->total }}</td>
                    <td>
                      <form action="{{ route('orders.destroy',$order->id) }}" method="POST">
                          <a class="btn btn-primary" href="{{ url('order/status',$order->id) }}">Deliver</a>
                          <a class="btn btn-info" href="{{ route('orders.show',$order->id) }}">Show</a>
                          @can('order-edit')
                          @endcan
                          @csrf
                          @method('DELETE')
                          @can('order-delete')
                          <button type="submit" class="btn btn-danger">Delete</button>
                          @endcan
                      </form>
                    </td>
                  </tr>
                @endforeach
                </table>
                {!! $orders->render() !!}
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