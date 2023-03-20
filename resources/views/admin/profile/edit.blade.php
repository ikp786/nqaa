@extends('admin.layouts.master')

@section('title', 'Edit Profile')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .iti {
            width: 100% !important;
        }
    </style>
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
                <a class="opacity-3 text-dark" href="javascript:;">
                    <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>shop </title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                    <g transform="translate(0.000000, 148.000000)">
                                        <path d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                                        <path d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Profile</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Edit Profile</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Edit Profile</h6>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 text-center">
            <h3 class="mt-5">Edit Profile</h3>
            <h5 class="text-secondary font-weight-normal"></h5>
            <div class="mb-5">
            {{--@if($errors->any())
                {!! implode('', $errors->all('<div class="text-danger">:message</div>')) !!}
            @endif--}}
            <!--form panels-->
                <div class="row">
                    <div class="col-12 col-lg-12 m-auto">
                        <form method="POST" action="{{route('admin.profile.store')}}">
                        @csrf
                        <!--single form panel-->
                            <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                                <div class="row">
                                    <div class="row mt-4">
                                        <div class="col-12 col-sm-6 mt-sm-0">
                                            {{--<div class="avatar avatar-xxl position-relative">
                                                <img id="preview" src="{{$data->image}}" class="border-radius-md" alt="" height="140px">
                                                <a href="javascript:;" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
                                                    <input name="image" accept="image/*" type="file" id="image" class="d-none"/>
                                                    <label for="image">
                                                        <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true" data-bs-original-title="Edit Image" aria-label="Edit Image"></i>
                                                        <span class="sr-only">Edit Image</span>
                                                    </label>
                                                </a>
                                            </div>--}}
                                            <div class="text-start">
                                                <input type="hidden" name="id" value="{{$data->id}}">
                                                <label>Name</label>
                                                <input name="name" value="{{old('name') ?? $data->name}}" class="form-control mb-3" type="text" placeholder="Eg. Your Name" />
                                                @error('name')
                                                <span class="text-danger text-xs">{{ $message }}</span><br/>
                                                @enderror
                                                <label>Email Address</label>
                                                <input name="email" value="{{old('email') ?? $data->email}}" class="form-control mb-3" type="email" placeholder="Eg. mail@email.com" />
                                                @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <label>Commission (%)</label>
                                                <input name="admin_commission" value="{{old('admin_commission') ?? setting('admin_commission')}}" min="0" max="100" class="form-control mb-3" type="number" placeholder="Enter Your Commission"/>
                                                @error('admin_commission')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 mt-sm-0">
                                            <div class="text-start">
                                                <label>Password</label>
                                                <input name="password" value="" class="form-control mb-3" type="password" placeholder="" />
                                                @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <label>Confirm Password</label>
                                                <input name="password_confirmation" value="" class="form-control mb-3" type="password" placeholder="" />
                                                @error('password_confirmation')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-row d-flex mt-4">
                                        <button class="btn bg-gradient-dark ms-auto mb-0 js-btn-next" type="submit" title="Next">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js" integrity="sha512-L3moecKIMM1UtlzYZdiGlge2+bugLObEFLOFscaltlJ82y0my6mTUugiz6fQiSc5MaS7Ce0idFJzabEAl43XHg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var input = window.intlTelInput(document.querySelector("#phone_number"), {
            separateDialCode: true,
            preferredCountries: ['in', 'us', 'gb'],
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        }).setNumber('{{$data->phone_code.$data->phone_number}}');//.setCountry("in");

        /*$('form').on('submit', function(e) {
            e.preventDefault();
            // alert(input);
            var my_phone = document.querySelector("#phone_number");
            // alert($('#phone_number').val());
            if (my_phone.value.trim()) {
                //if (input.isValidNumber()) {
                    var phone_number = $('#phone_number').val();
                    var full_number = input.getNumber(intlTelInputUtils.numberFormat.E164);
                    var phone_code = full_number.replace(phone_number,'');
                    alert(full_number);
                /!*} else {
                    e.preventDefault();
                    toastr.error('Invalid phone number.');
                }*!/
            }
        });*/
    </script>
    <script>
        image.onchange = evt => {
            const [file] = image.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }
    </script>
@endsection

