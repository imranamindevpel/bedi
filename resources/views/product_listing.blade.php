@extends('layouts.front_app')
@section('content')
    @if ($message = Session::get('false'))
    <div class="alert alert-danger" role="alert">
      <p class="text-green-800">{{ $message }}</p>
    </div>
    @endif
    @foreach ($products as $product)
    <div class="col-md-4 p-1">
        <div class="card">
            <img class="card-img-top" src="{{ url($product->image) }}" alt="Card image cap" width="220px" height="220px">
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }} ({{$product->quantity}})</h5>
                <p class="card-text">{{ $product->detail }}</p>
                @if(!$product->quantity == 0)
                <form class="product-form" action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $product->id }}" name="id">
                    <input type="hidden" value="{{ $product->name }}" name="name">
                    <input type="hidden" value="{{ $product->price }}" name="price">
                    <input type="hidden" value="{{ $product->image }}"  name="image">
                    <input type="hidden" value="1" name="quantity">
                    <input type="hidden" value="{{$product->quantity}}" name="stock">
                    <button class="btn btn-primary disable-btn" onclick="handleClick()" type="submit">Add To Cart</button>
                </form>
                @else
                <button class="btn btn-danger">Out of Stock</button>
                @endif
            </div>
        </div>
    </div>
    @endforeach

<script>
  var buttons = document.getElementsByClassName('disable-btn');
  var form = document.getElementsByClassName('product-form');

  function handleClick(event) {
    event.preventDefault();
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].disabled = true;
    }
    var currentForm = this.closest('.product-form');
    currentForm.submit();
  }

  for (var i = 0; i < buttons.length; i++) {
    buttons[i].addEventListener('click', handleClick);
  }
</script>
@endsection
