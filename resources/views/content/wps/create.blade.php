@extends('layouts/contentNavbarLayout')

@section('title', 'Add WP Customer')

@section('content')
<!-- Custom style1 Breadcrumb -->
<nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
          <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="breadcrumb-item active">Add WP Customer</li>
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
        <form action="{{ url('/work-permit') }}" method="post">
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
            <label class="col-sm-2 col-form-label" for="basic-default-company">Expiration Date</label>
            <div class="input-group">
                <input class="form-control" name="wpExpireDate" id="exp_date" type="date" value="<?php echo date('Y-12-31'); ?>" readonly/>
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
                <a href="{{ route('work-permit.index') }}" style="color: white">All Work Permit Customer</a>
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
              {!! csrf_field() !!}
              <input type="hidden" name="depositType" value="Null">
              <input type="hidden" name="deposit" value="Null">
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Customer Name*</label>
                <div class="input-group">
                <input type="text" name="CustomerName" class="form-control" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp" />
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Customer Detail*</label>
                <div class="input-group">
                  <input class="form-control" name="nationality" placeholder="-- Nationality --" list="nationality" type="text" />
                  <datalist id="nationality">
                    @foreach ($countriesList as $country)
                      <option value="{{ $country->nationality }}"> {{ $country->nationality }} </option>
                    @endforeach
                  </datalist> 
                  <select id="gender" name="gender" class="select2 form-select">
                    <option value="N/A">-- Select Gender --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Female">In-Between</option>
                  </select>
                </div>
              </div>
              <div class="row mb-3" id="newContactDiv">
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
                <label for="exampleFormControlTextarea1" class="form-label">Remark/Comment</label>
                <div class="input-group">
                    <textarea class="form-control" name="comment" id="exampleFormControlTextarea1" rows="1"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Save</button>
                  <button type="button" class="btn btn-dark">
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
