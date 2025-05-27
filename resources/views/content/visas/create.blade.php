@extends('layouts/contentNavbarLayout')

@section('title', 'Add Visa Customer')

@section('content')
<!-- Custom style1 Breadcrumb -->
<nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
          <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">Add Visa Customer</li>
      </ol>
    </nav>
    <!--/ Custom style1 Breadcrumb -->
    
    <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-body">
        {{-- Message --}}
        @include('layouts.sections.messages')
        {{-- End Message --}}
        <form action="{{ url('/visas') }}" method="post">
          {!! csrf_field() !!}
          <div class="row mb-3">

            <label class="col-sm-2 col-form-label" for="basic-default-name">Customer Name</label>
            <div class="input-group">
              <input name="CustomerName" class="form-control" list="customers" id="CustomerName" placeholder="Type to search...">
              <datalist id="customers">
                @foreach ($customers as $customer)
                    <option value="{{ $customer->CustomerName }}">
                @endforeach
              </datalist>
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
              Add New Customer
              </button>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">General Information</label>
            <div class="input-group">
              <span class="input-group-text">Passport Amount</span>
                <input type="text" name="ppAmount" class="form-control" id="defaultFormControlInput" aria-describedby="defaultFormControlHelp" />
                <span class="input-group-text">Visa Type</span>
                <select class="form-select" name="visaType" id="exampleFormControlSelect1" aria-label="Default select example">
                    <option value="EB" selected>EB</option>
                    <option value="ER">ER</option>
                    <option value="EG">EG</option>
                    <option value="EP">EP</option>
                    <option value="ES">ES</option>
                </select>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Expiration Date</label>
            <div class="input-group">
            <span class="input-group-text">Expiration Date</span>
                <input class="form-control" name="expireDate" type="date" id="exp_date" placeholder="" value=""/>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Incharge Staff</label>
            <div class="input-group">
              <select class="form-select" name="staffID" aria-label="staffID">
                <option value="">-- Staff Incharge --</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-warning">Save</button>
              <button type="button" class="btn btn-primary">
                <a href="{{ route('visas.index') }}" style="color: white">All Visa Customer</a>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="staticBackdrop" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Add New Customer</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="card-body">
                  <form action="{{ url('customers') }}" method="post">
                  @csrf
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="basic-default-name">Customer Name <strong class="text-danger">*</strong> </label>
                      <div class="input-group">
                      <input type="text" name="CustomerName" class="form-control" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp" />
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label">Customer Detail <strong class="text-danger">*</strong></label>
                      <div class="input-group">
                        <input class="form-control" name="nationality" placeholder="-- Nationality --" list="nationality" type="text" />
                        <datalist id="nationality">
                          @foreach ($countriesList as $country)
                            <option value="{{ $country->nationality }}"> {{ $country->nationality }} </option>
                          @endforeach
                        </datalist>                    
                        
                        <select id="gender" name="gender" class="form-select">
                          <option value="N/A">-- Select Gender --</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="In-Between">In-Between</option>
                        </select>
                      </div>
                    </div>
                    <div class="row mb-3" id="newContactDiv">
                      <label class="col-sm-2 col-form-label">Customer Contact <strong class="text-danger">*</strong></label>
                      <div class="input-group">
                        <select name="inputs[0][contactType]" class="form-select">
                          <option value="N/A">-- Contact Type --</option>
                            <option value="Mobile Phone">Mobile Phone</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Telegram">Telegram</option>
                            <option value="WhatsApp">WhatsApp</option>
                            <option value="Others">Others</option>
                          </select>
                        <input type="text" name="inputs[0][contactDetail]" class="form-control" id="contactDetail" placeholder="Contact Details" aria-describedby="contactDetail" />
                        <button type="button" id="add-button" name="add-button" class="btn btn-warning">Add More</button>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                      <div class="input-group">
                          <textarea class="form-control" name="address" id="exampleFormControlTextarea1" rows="3"></textarea>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="exampleFormControlTextarea1" class="form-label">Remark/Comment</label>
                      <div class="input-group">
                          <textarea class="form-control" name="comment" id="exampleFormControlTextarea1" rows="1"></textarea>
                      </div>
                    </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-10">
                        <button type="submit" class="btn btn-warning">Save</button>
                        <button type="button" class="btn btn-primary">
                          <a href="{{ route('customers.index') }}" style="color: white">View All Customer</a>
                        </button>
                      </div>
                    </div>
                  </form>
              </div>
            </div>
        </div>
      </div>
    </div>
</div>

<script>
  var i = 0;
  $('#add-button').click(function () {
    ++i;
    $('#newContactDiv').append (
      `
        <div class="input-group mt-3" id="newContactDivPlus">
          <select name="inputs[`+i+`][contactType]" class="form-select">
            <option value="N/A">-- Contact Type --</option>
              <option value="Mobile Phone">Mobile Phone</option>
              <option value="Facebook">Facebook</option>
              <option value="Telegram">Telegram</option>
              <option value="WhatsApp">WhatsApp</option>
              <option value="Others">Others</option>
          </select>
          <input type="text" name="inputs[`+i+`][contactDetail]" placeholder="Contact Details" class="form-control" />
          <button type="button" id="rm-button" name="rm-button" class="btn btn-danger">Remove</button>
        </div>
      `);
  });

  $(document).on('click', '#rm-button', function() {
    $(this).parents('#newContactDivPlus').remove();
  });
</script>

<script type="text/javascript">
  const expireDate = document.getElementById("exp_date");
  const remindDate = document.getElementById("reminderDate");
  console.log(expireDate);

  expireDate.addEventListener("change", () => {
    const date = new Date(expireDate.value);
    remindDate.value = date.toISOString().slice(0, 10) - 1;
  });
</script>
@endsection
