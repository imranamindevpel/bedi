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
            <h1>Products</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Products</li>
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
              @can('product-create')
              <a type="button" class="btn btn-sm btn-info float-right" href="{{ route('products.create') }}">Create New Product</a>
              @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($products as $key => $product)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $product->name }}</td>
                    <td>
                      <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                          <a class="btn btn-info" href="{{ route('products.show',$product->id) }}">Show</a>
                          @can('product-edit')
                          <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
                          @endcan
                          @csrf
                          @method('DELETE')
                          @can('product-delete')
                          <button type="submit" class="btn btn-danger">Delete</button>
                          @endcan
                      </form>
                    </td>
                  </tr>
                @endforeach
                </table>
                {!! $products->render() !!}
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