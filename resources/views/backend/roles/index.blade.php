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
            <h1>Roles</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Roles</li>
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
              @can('role-create')
              <a type="button" class="btn btn-sm btn-info float-right" href="{{ route('roles.create') }}">Create New Role</a>
              @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($roles as $key => $role)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                      <form action="{{ route('roles.destroy',$role->id) }}" method="POST">
                          <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
                          @can('role-edit')
                          <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                          @endcan
                          @csrf
                          @method('DELETE')
                          @can('role-delete')
                          <button type="submit" class="btn btn-danger">Delete</button>
                          @endcan
                      </form>
                    </td>
                  </tr>
                @endforeach
                </table>
                {!! $roles->render() !!}
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