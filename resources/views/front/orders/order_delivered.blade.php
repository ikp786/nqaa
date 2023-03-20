<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .outr-box{
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            margin: 50px auto;
            box-shadow: 0 0 20px #00000040;
        }
        .ordr-dtl{
            padding: 20px 0;
            border-bottom: 1px solid #ddd;
        }
        .ordr-dtl label{
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
            color: #000;
        }
        .ordr-dtl p{
            margin: 0;
            font-size: 16px;
        }
        .ordr-dtl input{
            width: 100%;
            border: none;
        }
        .com-le-btn{
            text-align: center;
            margin-top:40px ;
        }
        .com-le-btn button{
            background-color: #000;
            padding: 14px 40px;
            color: #fff;
            font-size: 16px;
            border-radius: 50px;
            border: none;
        }
    </style>
</head>
<body>

    
    @if(Session::has('Failed'))
<div class="alert alert-danger alert-dismissible fade show">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
        <line x1="15" y1="9" x2="9" y2="15"></line>
        <line x1="9" y1="9" x2="15" y2="15"></line>
    </svg>
    <strong>Error!</strong> {{Session::get('Failed')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
    </button>
</div>
@endif

@if(Session::has('Success'))
<div class="alert alert-success alert-dismissible fade show">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
        <polyline points="9 11 12 14 22 4"></polyline>
        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
    </svg>
    <strong>Success!</strong> {{Session::get('Success')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
    </button>
</div>
@endif

<form method="POST" autocomplete="off" action="{{ route('admin.orders.upload_order_image',$order->id) }}" enctype="multipart/form-data" >
    @csrf
    <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">
    <div class="outr-box">
        <div class="ordr-dtl">
            <label for="">Order ID</label>
            <p>{{ $order->order_number }}</p>
        </div>
        <div class="ordr-dtl">
            <label for="">Address</label>
            <p>{{ $order->address }}</p>
        </div>
        <div class="ordr-dtl">
            <label for="">Upload File</label>
            <input type="file" name="image" id="image">
        </div>
        @error('image')
    <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="ordr-dtl">
            <label for="">Upload File</label>
            <input type="file" name="image2" id="image2">
        </div>
        @error('image2')
    <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="com-le-btn">
            <button type="submit" >Complete</button>
        </div>
    </div>
</form>
</body>
</html>