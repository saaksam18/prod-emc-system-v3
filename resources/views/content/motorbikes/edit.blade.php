@extends('layouts/contentNavbarLayout')

@section('title', 'Motorbikes')

@section('content')
<!-- Custom style1 Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}">Home</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('motorbikes.index') }}">Motorbike Stock</a>
      </li>
      <li class="breadcrumb-item active">Edit Motorbike</li>
    </ol>
</nav>
      <!--/ Custom style1 Breadcrumb -->

<div class="col-xxl">
    <div class="card mb-4">
        <div class="card-body">
          {{-- Message --}}
          @include('layouts.sections.messages')
          {{-- End Message --}}

            {!! Form::model($motorbike, ['method' => 'PUT','route' => ['motorbikes.update', $motorbike->motorID]]) !!}
            <form action="/motorbikes/{motorbike}/update" method="PUT">
                @csrf
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-company">General Information*</label>

                    <input type="hidden" name="motorID" value="{{ $motorbike->motorID }}">

                    <div class="input-group">
                        <span class="input-group-text">Motorbike No.*</span>
                        {!! Form::text('motorno', null, array('class' => 'form-control')) !!}
                        <span class="input-group-text">Year*</span>
                        {!! Form::text('year', null, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="input-group">
                        <span class="input-group-text">Plate No.*</span>
                        {!! Form::text('plateNo', null, array('class' => 'form-control')) !!}
                        <span class="input-group-text">Engine No.*</span>
                        {!! Form::text('engineNo', null, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="input-group">
                        <span class="input-group-text">Chassis No.*</span>
                        {!! Form::text('chassisNo', null, array('class' => 'form-control')) !!}
                        <span class="input-group-text">Motorbike Color*</span>
                        {!! Form::text('motorColor', null, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="input-group">
                      <span class="input-group-text">Motorbike Model*</span>
                      <input class="form-control" name="motorModel" list="datalistOptions" id="exampleDataList" placeholder="" value="{{ $motorbike->motorModel }}" required/>
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
                        <select class="form-select" name="motorType" aria-label="Default select example">
                          <option value="1" {{ $motorbike->motorType == 1 ? 'selected' : '' }}>Big AT</option>
                            <option value="2" {{ $motorbike->motorType == 2 ? 'selected' : '' }}>Auto</option>
                            <option value="3" {{ $motorbike->motorType == 3 ? 'selected' : '' }}>50cc AT</option>
                            <option value="4" {{ $motorbike->motorType == 4 ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>
                  </div>
        
                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-company">Operation Information</label>
                    <div class="input-group">
                        <span class="input-group-text">Purchase Date*</span>
                        {!! Form::date('purchaseDate', null, array('class' => 'form-control')) !!}
                        {{-- <input class="form-control" name="purchaseDate" type="date" value="{{ old('purchaseDate', (string)$motorbike->purchaseDate) }}" id="purchaseDate" required/> --}}
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="input-group">
                        <span class="input-group-text">Compensation Price*</span>
                        {!! Form::text('compensationPrice', null, array('class' => 'form-control')) !!}
                        <select class="form-select" name="motorStatus" aria-label="Default select example">
                            <option value="1" {{ $motorbike->motorStatus == 1 ? 'selected' : '' }}>In Stock</option>
                            <option value="2" {{ $motorbike->motorStatus == 2 ? 'selected' : '' }}>On Rent</option>
                            <option value="3" {{ $motorbike->motorStatus == 3 ? 'selected' : '' }}>Sold</option>
                            <option value="4" {{ $motorbike->motorStatus == 4 ? 'selected' : '' }}>Stolen / Lost</option>
                        </select>
                    </div>
                  </div>
        
                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-name">Total Purchase Price*</label>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      {!! Form::text('totalPurchasePrice', null, array('class' => 'form-control')) !!}
                      <span class="input-group-text">.00</span>
                    </div>
                  </div>

                    <button type="submit" class="btn btn-primary">Update</button>
            </form>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection