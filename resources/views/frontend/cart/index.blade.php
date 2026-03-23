<!-- Giỏ hàng -->
 @extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2>🛒 Giỏ hàng của bạn</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(empty($cart))
        <p>Giỏ hàng đang trống.</p>
    @else

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th width="120">Giá</th>
                <th width="180">Số lượng</th>
                <th width="150">Thành tiền</th>
                <th width="80">Xóa</th>
            </tr>
        </thead>

        <tbody>

        @php $total = 0; @endphp

        @foreach($cart as $id => $item)

        @php
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        @endphp

        <tr>

            <!-- Sản phẩm -->
            <td>
                <div class="d-flex align-items-center gap-3">

                    @if($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}"
                             width="70">
                    @endif

                    <div>
                        <strong>{{ $item['name'] }}</strong><br>
                        Size: {{ $item['size'] ?? '—' }}
                    </div>

                </div>
            </td>

            <!-- Giá -->
            <td>{{ number_format($item['price']) }}đ</td>

            <!-- Số lượng -->
            <td>

                <form action="{{ route('cart.update') }}" method="POST" class="d-flex">
                    @csrf

                    <input type="hidden" name="id" value="{{ $id }}">

                    <input type="number"
                           name="quantity"
                           value="{{ $item['quantity'] }}"
                           min="1"
                           class="form-control me-2">

                    <button class="btn btn-primary btn-sm">
                        Cập nhật
                    </button>
                </form>

            </td>

            <!-- Thành tiền -->
            <td>{{ number_format($subtotal) }}đ</td>

            <!-- Xóa -->
            <td>
                <form action="{{ route('cart.remove') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">

                    <button class="btn btn-danger btn-sm">
                        Xóa
                    </button>
                </form>
            </td>

        </tr>

        @endforeach

        </tbody>
    </table>

    <div class="text-end fs-4">
        <strong>Tổng tiền: {{ number_format($total) }}đ</strong>
    </div>

    <div class="text-end mt-3">
        <a href="{{ route('checkout') }}" class="btn btn-success btn-lg">
            Thanh toán
        </a>
    </div>

    @endif

</div>
@endsection