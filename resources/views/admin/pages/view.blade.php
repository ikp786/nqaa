@extends('admin.layouts.main')
@section('style')
<style>
.nav.nav-pills .nav-link.active {
    background: #ff5c39;
    color: white;
}
</style>
@endsection
@section('content')
<div class="card mt-4" id="basic-info">
   <div class="card-header">
      <h5>Host Details
         <a href="{{route('host')}}" class="btn btn-sm btn-primary float-right">List</a>
      </h5>
   </div>
   <div class="card-body pt-0">
        <div class="container pb-lg-9 pb-10  ">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-7 mx-auto text-center">
                    <div class="nav-wrapper position-relative z-index-2">
                        <ul class="nav nav-pills nav-fill flex-row p-1" id="tabs-pricing" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 active" id="tabs-iconpricing-tab-1" data-bs-toggle="tab" href="#monthly" role="tab" aria-controls="monthly" aria-selected="true">
                                Host Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0" id="tabs-iconpricing-tab-2" data-bs-toggle="tab" href="#annual" role="tab" aria-controls="annual" aria-selected="false">
                                Events
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
      <div class="mt-n8">
         <div class="container">
            <div class="tab-content tab-space">
               <div class="tab-pane active" id="monthly">
                  <div class="row fiterDiv">
                     <div class="col-lg-12 mb-lg-0 mb-4">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label ">Name</label>
                                <div class="input-group">
                                    {{$data->name}}
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label ">Email</label>
                                <div class="input-group">
                                    {{$data->email}}
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-4">Phone Number</label>
                                <div class="input-group">
                                ({{$data->country_code}}) {{$data->phone}}
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-4">Status</label>
                                <div class="input-group">
                                    <?php if($data->status == '1'){echo "Active";} ?>
                                    <?php if($data->status == '0'){echo "Inactive";} ?>
                                </div>
                            </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane" id="annual">
                  <div class="row">
                     <div class="col-lg-12 mb-lg-0 mb-4">
                       <p>test1 </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
@endsection