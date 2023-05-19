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
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
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
              <a type="button" class="btn btn-sm btn-info float-right" href="{{ route('users.create') }}">Create New User</a>
              @endcan
              </div>
              <!-- /.card-header -->
              <div class="card-body">

              <table id="table1" class="display">
                </table>
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
<script>
    $(document).ready(function() {
        list();
    });

    function list() { 
        $.ajax({
            url: "/users/get_users_data",
            type: "get",
            dataType: "json",
            success: function(response) {
                var columns = [];
                for (var key in response.data[0]) {
                    var header = key.replace(/_/g, ' '); // Replace underscores with spaces
                    columns.push({"title": header, "data": key});
                }
                $('#table1').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "/users/get_users_data",
                        "type": "get"
                    },
                    "columns": columns
                });
            }
        });
    }
</script>
@endsection