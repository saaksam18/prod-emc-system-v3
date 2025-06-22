@extends('layouts/contentNavbarLayout')

@section('title', 'Add Motor Rental')

@section('content')
<!-- Custom style1 Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item">
      <a href="{{ route('home') }}">Home</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('rentals.index') }}">Rental Management</a>
    </li>
    <li class="breadcrumb-item active">Add Motor Transaction</li>
  </ol>
</nav>
    <!--/ Custom style1 Breadcrumb -->

    <!-- Basic Layout -->
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        {{-- Message --}}
        @include('layouts.sections.messages')
        {{-- End Message --}}
        <form action="{{ url('rentals') }}" method="post">
          {!! csrf_field() !!}
          <div class="row mb-3">
            <label for="CustomerName" class="form-label">Customer Name <span class="text-danger">*</span></label>
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
            <label for="CustomerName" class="form-label">Deposit <span class="text-danger">*</span></label>
            <div class="input-group" id="newDepositDiv">
              <select name="inputs[0][currDepositType]" class="form-select">
                <option value="N/A">-- Deposit Type --</option>
                <option value="Passport">Passport</option>
                <option value="Money">Money</option>
                <option value="Others">Others</option>
              </select>
              <input type="text" id="deposit" name="inputs[0][currDeposit]" placeholder="New Deposit" class="form-control">
              <button type="button" id="add-deposit-button" name="add-button" class="btn btn-warning">Add More</button>
            </div>
            <label for="CustomerName" id="important" class="form-label mt-1" style="display: none;">
                <span>
                  <li>
                    Please don't use <span class="text-danger">($)</span> Sign for money deposit
                  </li>
                </span>
                <span>
                  <li>
                    If customer rent more than 1 motorbikes with 1 deposit please use <span class="text-danger">Others</span> for second motorbike
                  </li>
                </span>
            </label>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="motorbikeNo">Motorbike No. <span class="text-danger">*</span></label>
            <div class="input-group">
              <input name="motorbikeNo" class="form-control" list="motorlist" id="motorbikeNo" placeholder="Type to search...">
              <datalist id="motorlist">
                @foreach ($motorbikes as $motorbike)
                    <option value="{{ $motorbike->motorno }}">
                @endforeach
              </datalist>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Rental Date <span class="text-danger">*</span></label>
            <div class="input-group">
            <span class="input-group-text">Start Date</span>
              <input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" id="rental_date" name="rentalDay" onchange="cal()"/>
            <span class="input-group-text">Return Date</span>
              <input class="form-control" type="date" id="return_date" name="returnDate" onchange="cal()"/>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Period</label>
            <div class="input-group">
            <span class="input-group-text">Coming Date</span>
              <input class="form-control" type="date" value="" id="html5-date-input" name="commingDate"/>
            <span class="input-group-text">Rental Period</span>
              <input class="form-control" type="text" id="peroidOfRent" placeholder="" name="rentalPeriod" readonly/>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Price <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="text" class="form-control" placeholder="Amount" name="price" aria-label="Amount (to the nearest dollar)" />
              <span class="input-group-text">.00</span>
              <select class="form-select" name="staffId" aria-label="staffId">
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
                <a href="{{ route('rentals.index') }}" style="color: white">View All Transaction</a>
              </button>
            </div>
          </div>
        </form>
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

<script>
  var i = 0;
  $('#add-deposit-button').click(function () {
    ++i;
    $('#newDepositDiv').append (
      `
        <div class="input-group mt-3" id="newDepositDivPlus">
          <select name="inputs[`+i+`][currDepositType]" class="form-select">
            <option value="N/A">-- Deposit Type --</option>
            <option value="Passport">Passport</option>
            <option value="Money">Money</option>
            <option value="Others">Others</option>
          </select>
          <input type="text" name="inputs[`+i+`][currDeposit]" placeholder="New Deposit" class="form-control" />
          <button type="button" id="rm-deposit-button" name="rm-button" class="btn btn-danger">Remove</button>
        </div>
      `);
  });

  $(document).on('click', '#rm-deposit-button', function() {
    $(this).parents('#newDepositDivPlus').remove();
  });
</script>

<script type="text/javascript">
  function GetDays(){
          var returnDate = new Date(document.getElementById("return_date").value);
          var rentalDate = new Date(document.getElementById("rental_date").value);
          return parseInt((returnDate - rentalDate) / (24 * 3600 * 1000));
  }

  function cal(){
  if(document.getElementById("return_date")){
      document.getElementById("peroidOfRent").value=GetDays();
  }  
}
</script>
<script>
  const depositInput = document.getElementById('deposit');
  const importantLabel = document.getElementById('important');

  depositInput.addEventListener('click', () => {
    importantLabel.style.display = 'block'; // Show the label
  });

  // To hide the label when clicking outside the input (optional)
  document.addEventListener('click', (event) => {
    if (!depositInput.contains(event.target)) {
      importantLabel.style.display = 'none'; // Hide the label
    }
  });
</script>
@endsection