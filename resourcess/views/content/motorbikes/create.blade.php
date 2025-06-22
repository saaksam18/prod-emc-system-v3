@extends('layouts/contentNavbarLayout')

@section('title', 'Add Motor Rental')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
<script src="{{asset('assets/vendor/js/bootstrap-select-country.js')}}"></script>
@endsection

@section('content')
<!-- Custom style1 Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item">
      <a href="{{ route('home') }}">Home</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('motorbikes.index') }}">Motorbikes Management</a>
    </li>
    <li class="breadcrumb-item active">Add New Motorbike</li>
  </ol>
</nav>
    <!--/ Custom style1 Breadcrumb -->
    <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card">
      <div class="card-body">
        {{-- Message --}}
        @include('layouts.sections.messages')
        {{-- End Message --}}
        <form action="{{ url('motorbikes') }}" method="post">
          {!! csrf_field() !!}
          <div class="row">
              <label class="col-lg-12 col-form-label" for="basic-default-company">General Information<strong class="text-danger">*</strong></label>
                <div class="col-lg-3 mb-3">
                  <div class="input-group">
                    <span class="input-group-text">Motorbike No.<strong class="text-danger">*</strong></span>
                      <input type="text" name="motorno" class="form-control" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp" required/>
                  </div>
                </div>
                <div class="col-lg-3 mb-3">
                  <div class="input-group">
                    <span class="input-group-text">Year<strong class="text-danger">*</strong></span>
                      <input type="text" name="year" class="form-control" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp" required/>
                  </div>
                </div>
                <div class="col-lg-3 mb-3">
                  <div class="input-group">
                    <span class="input-group-text">Plate No.<strong class="text-danger">*</strong></span>
                    <input type="text" name="plateNo" class="form-control" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp" required/>
                  </div>
                </div>
                <div class="col-lg-3 mb-3">
                  <div class="input-group">
                    <select class="form-select" name="motorType" aria-label="Default select example">
                      <option value="">-- Motorbike Type --</option>
                      <option value="1">Big AT</option>
                      <option value="2">Auto</option>
                      <option value="3">50cc AT</option>
                      <option value="4">Manual</option>
                    </select>
                  </div>
                </div>
            <div class="row mb-3">
              <div class="col-lg-3 mb-3">
                <div class="input-group">
                  <span class="input-group-text">Motorbike Model<strong class="text-danger">*</strong></span>
                  <input class="form-control" name="motorModel" list="datalistOptions" id="exampleDataList" placeholder="" required/>
                    <datalist id="datalistOptions">
                      <option value="Honda PCX">
                      <option value="Honda Air Blade">
                      <option value="Honda Click">
                      <option value="Honda Today">
                      <option value="Honda Vision">
                      <option value="Yamaha Fino">
                      <option value="Yamaha Cygnus">
                      <option value="Yamaha Mio">
                      <option value="Suzuki Smash">
                    </datalist>
                </div>
              </div>
              <div class="col-lg-3 mb-3">
                <div class="input-group">
                  <span class="input-group-text">Engine No.<strong class="text-danger">*</strong></span>
                  <input type="text" name="engineNo" class="form-control" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp" required/>
                </div>
              </div>
              <div class="col-lg-3 mb-3">
                <div class="input-group">
                  <span class="input-group-text">Chassis No.<strong class="text-danger">*</strong></span>
                  <input type="text" name="chassisNo" class="form-control" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp" required/>
                </div>
              </div>
              <div class="col-lg-3 mb-3">
                <div class="input-group">
                  <span class="input-group-text">Motorbike Color<strong class="text-danger">*</strong></span>
                  <input type="text" name="motorColor" class="form-control" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp" required/>
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-lg-12 col-form-label" for="basic-default-company">Operation Information</label>
            <div class="input-group">
                <span class="input-group-text">Purchase Date<strong class="text-danger">*</strong></span>
                <input class="form-control" name="purchaseDate" type="date" value="<?php echo date('d-m-Y'); ?>" id="html5-date-input" required/>
            </div>
          </div>
          <div class="row mb-3">
            <div class="input-group">
                <span class="input-group-text">Compensation Price<strong class="text-danger">*</strong></span>
                <input type="text" name="compensationPrice" class="form-control" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp" required/>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Total Purchase Price<strong class="text-danger">*</strong></label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="text" name="totalPurchasePrice" class="form-control" placeholder="Amount" aria-label="Amount (to the nearest dollar)" required/>
              <span class="input-group-text">.00</span>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Save</button>
              <button type="button" class="btn btn-dark">
                <a href="{{ route('motorbikes.index') }}" style="color: white">View All Motorbike</a>
              </button>
            </div>
          </div>
        </form>
      </div>
  </div>
</div>
</div>
@endsection
