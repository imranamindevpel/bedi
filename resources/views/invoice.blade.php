@extends('layouts.front_app')
@section('content')
    @if (@$message = Session::get('success'))
    <div class="alert alert-primary" role="alert">
      <p class="text-green-800">{{ @$message }}</p>
    </div>
    @endif
    @if (@$message = Session::get('false'))
    <div class="alert alert-danger" role="alert">
      <p class="text-green-800">{{ @$message }}</p>
    </div>
    @endif         

    <div class="container">
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="text-align:center;">
          <h1>Thank you for your order!</h1>
          <p>Your order has been successfully placed.</p>
          <p>We appreciate your business and are thrilled to have you as our customer.</p>
          <p>You can now view and download your invoice using the link below:</p>
          <a href="{{ @$invoice_url }}" class="btn btn-primary text-white">View Invoice</a>
          <p>Order Details:</p>
          <p><b>Order Number:</b> {{ $id }}</p>
          <p><b>Order Date:</b> {{ $amount }}</p>
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>
@endsection