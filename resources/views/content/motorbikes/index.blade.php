@extends('layouts/contentNavbarLayout')

@section('title', 'Motorbikes Management')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        <li class="breadcrumb-item active">Motorbikes Management</li>
    </ol>
</nav>

<div class="row mb-3">
    <div class="col-lg-12">
        <button type="button" class="btn btn-warning">
        <a href="{{ route('motorbikes.create') }}" style="color: white">Create New Motorbike</a>
        </button>
        <button type="button" class="btn btn-dark">
        <a href="{{ route('print-stock') }}" style="color: white">Print Stock</a>
        </button>
    </div>
</div>
    
{{-- Header --}}
{{-- <div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-primary">
                            <tr>
                                <td class="text-white text-center">Type</td>
                                <td class="text-white text-center">Total</td>
                                <td class="text-white text-center">In Stock</td>
                                <td class="text-white text-center">On Rent</td>
                                <td class="text-white text-center">% of Rental</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Big Auto</td>
                                <td class="text-center">{{ $bigATs }}</td>
                                <td class="text-center">
                                    {{ $bigATis }}  @if ($tempBigATis != NULL)
                                        (TM Return: {{ $tempBigATis }})
                                    @endif
                                </td>
                                <td class="text-center">{{ $bigATor }}</td>
                                <td class="text-center">{{ $totalBigATPercentageFormatted }}%</td>
                            </tr>
                            <tr>
                                <td>Auto</td>
                                <td class="text-center">{{ $ats }}</td>
                                <td class="text-center">
                                    {{ $atis }}  @if ($tempATis != NULL)
                                    (TM Return: {{ $tempATis }})
                                    @endif
                                </td>
                                <td class="text-center">{{ $ator }}</td>
                                <td class="text-center">{{ $totalATPercentageFormatted }}%</td>
                            </tr>
                            <tr>
                                <td>50cc Auto</td>
                                <td class="text-center">{{ $ccATs }}</td>
                                <td class="text-center">
                                    {{ $ccATis }}  @if ($tempCCATis != NULL)
                                    (TM Return: {{ $tempCCATis }})
                                    @endif
                                </td>
                                <td class="text-center">{{ $ccATor }}</td>
                                <td class="text-center">{{ $total50ccATPercentageFormatted }}%</td>
                            </tr>
                            <tr>
                                <td>Manual</td>
                                <td class="text-center">{{ $mts }}</td>
                                <td class="text-center">
                                    {{ $mtis }}  @if ($tempMTis != NULL)
                                    (TM Return: {{ $tempMTis }})
                                    @endif
                                </td>
                                <td class="text-center">{{ $mtor }}</td>
                                <td class="text-center">{{ $totalMTPercentageFormatted }}%</td>
                            </tr>
                            <tr class="bg-dark">
                                <td class="text-white text-center">Total</td>
                                <td class="text-white text-center">{{ $totalMotors }}</td>
                                <td class="text-white text-center">{{ $totalInstock }}</td>
                                <td class="text-white text-center">{{ $totalOnRent }}</td>
                                <td class="text-white text-center">{{ $totalPercentageFormatted }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="p-2 text-center">
                <div class="card-title">
                    <h5 class="text-nowrap mt-1 text-start ms-2">Motorbikes</h5>
                    <div class="row">
                        <div class="col-3">
                            <span class="badge bg-label-warning rounded-pill mt-1">In Stock</span>
                            <h6 class="mb-0 mt-1">{{ $totalInstock }}</h6>
                        </div>
                        <div class="col-3">
                            <span class="badge bg-label-success rounded-pill mt-1">On Rent</span>
                            <h6 class="mb-0 mt-1">{{ $totalOnRent }}</h6>
                        </div>
                        <div class="col-3">
                            <span class="badge bg-label-primary rounded-pill mt-1">Temp. In Stock</span>
                            <h6 class="mb-0 mt-1">{{ $tempReturn }}</h6>
                        </div>
                        <div class="col-3">
                            <span class="badge bg-primary rounded-pill mt-1 ps-3 pe-3">Total</span>
                            <h6 class="mb-0 mt-1">{{ $totalMotors }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="p-2 text-center">
                <div class="card-title">
                    <h5 class="text-nowrap mt-1 text-start ms-2">Today Transactions</h5>
                    <div class="row">
                        <div class="col-6">
                            <span class="badge bg-success rounded-pill mt-1">Rent</span>
                            <h6 class="mb-0 mt-1">{{ $rent_today }} Scooters</h6>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-danger rounded-pill mt-1">Return</span>
                            <h6 class="mb-0 mt-1">{{ $return_today }} Scooters</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
{{-- End Header --}}

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header bg-primary text-white">Motorbike Informations</h5>
            {{-- Filter --}}
            <form action="" method="GET">
                <div class="ms-3 me-3">
                    <div class="row">
                        <label class="col-form-label">Filter</label>
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Motor No.</span>
                                <input name="motorno" class="form-control" list="motorno_list" id="motorno" value="{{ Request::get('motorno') }}" placeholder="Type to search...">
                                <datalist id="motorno_list">
                                    @foreach ($motorbike_no_drop as $motorbike)
                                        <option value="{{ $motorbike->motorno }}"> {{ $motorbike->motorno }} </option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <select class="form-select" name="motorType" id="motorType">
                                    <option value="">Type</option>
                                    <option value="1" @if (Request::get('motorType') == 1) selected @endif>Big AT</option>
                                    <option value="2" @if (Request::get('motorType') == 2) selected @endif>Auto</option>
                                    <option value="3" @if (Request::get('motorType') == 3) selected @endif>50cc AT</option>
                                    <option value="4" @if (Request::get('motorType') == 4) selected @endif>Manual</option>
                                </select>
                                <input name="motorModel" class="form-control" list="motorModel_list" id="motorModel" value="{{ Request::get('motorModel') }}" placeholder="Type to search...">
                                <datalist id="motorModel_list">
                                    @foreach ($motorbike_model_drop as $motorbike)
                                        <option value="{{ $motorbike->motorModel }}"> {{ $motorbike->motorModel }} </option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Color</span>
                                <input name="motorColor" class="form-control" list="motorColor_list" id="motorColor" value="{{ Request::get('motorColor') }}" placeholder="Type to search...">
                                <datalist id="motorColor_list">
                                    @foreach ($motorbike_color_drop as $motorbike)
                                        <option value="{{ $motorbike->motorColor }}"> {{ $motorbike->motorColor }} </option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Purchase Date</span>
                                <input class="form-control" type="date" name="purchaseDate" value="{{ Request::get('purchaseDate') }}" id="purchaseDate">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Motor Status</span>
                                <select class="form-select" name="motorStatus" id="motorStatus">
                                    <option value="">-- Status --</option>
                                    <option value="1" @if (Request::get('motorStatus') == 1) selected @endif>In Stock</option>
                                    <option value="2" @if (Request::get('motorStatus') == 2) selected @endif>On Rent</option>
                                    <option value="3" @if (Request::get('motorStatus') == 3) selected @endif>Sold</option>
                                    <option value="4" @if (Request::get('motorStatus') == 4) selected @endif>Stolen</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Price</span>
                                <input name="totalPurchasePrice" class="form-control" list="totalPurchasePrice_list" id="totalPurchasePrice" value="{{ Request::get('totalPurchasePrice') }}" placeholder="Type to search...">
                                <datalist id="totalPurchasePrice_list">
                                    @foreach ($motorbike_price_drop as $motorbike)
                                        <option value="{{ $motorbike->totalPurchasePrice }}"> {{ $motorbike->totalPurchasePrice }} </option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <button class="btn btn-warning">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            {{-- End Filter --}}
            <div class="ms-3 me-3">
                {{-- Message --}}
                @include('layouts.sections.messages')
                {{-- End Message --}}
                <label class="col-lg-12 col-form-label">Table Data</label>
            </div>
            <div class="table-responsive text-nowrap">
                @if (count($motorbikes) > 0)
                <table class="table table-hover table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th>
                                @sortablelink('motorno', 'No.')
                            </th>
                            <th>
                                @sortablelink('motorModel', 'Model')
                            </th>
                            <th>
                                @sortablelink('motorType', 'Type')
                            </th>
                            <th>
                                @sortablelink('motorStatus', 'Status')
                            </th>
                            <th>
                                @sortablelink('year', 'Year')
                            </th>
                            <th>
                                @sortablelink('purchaseDate', 'Purchase At')
                            </th>
                            <th>
                                @sortablelink('totalPurchasePrice', 'Purchase Price')
                            </th>
                            <th>
                                @sortablelink('plateNo', 'Plate No.')
                            </th>
                            <th>
                                @sortablelink('motorColor', 'Color')
                            </th>
                            <th>
                                @sortablelink('engineNo', 'Engine No.')
                            </th>
                            <th>
                                @sortablelink('chassisNo', 'Chassis No.')
                            </th>
                            <th>
                                @sortablelink('userID', 'Inputer')
                            </th>
                            <th class="text-primary" colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($motorbikes as $motorbike)
                            <tr>
                                <th class="text-center">
                                    <a href="{{ route('motorbikes.show',$motorbike->motorID) }}">{{ $motorbike->motorno }}</a>
                                </th>
                                <th>
                                    <a href="{{ route('motorbikes.show',$motorbike->motorID) }}">{{ $motorbike->motorModel }}</a>
                                </th>
                                @if ($motorbike->motorType == 1)
                                    <td>Big AT</td>
                                @elseif ($motorbike->motorType == 2)
                                    <td>Auto</td>
                                @elseif ($motorbike->motorType == 3)
                                    <td>50cc AT</td>
                                    @elseif ($motorbike->motorType == 4)
                                    <td>Manual</td>
                                @endif
                                @if ($motorbike->motorStatus == 1)
                                <td>
                                    <span class="badge bg-primary text-white">
                                        In Stock
                                    </span>
                                </td>
                                @elseif ($motorbike->motorStatus == 2)
                                <td>
                                    <span class="badge bg-success text-white">
                                        On Rent
                                    </span>
                                </td>
                                @elseif ($motorbike->motorStatus == 3)
                                <td>
                                    <span class="badge bg-danger text-white">
                                        Sold
                                    </span>
                                </td>
                                @elseif ($motorbike->motorStatus == 4)
                                <td>
                                    <span class="badge bg-danger text-white">
                                        Lost / Stolen
                                    </span>
                                </td>
                                @elseif ($motorbike->motorStatus == 5)
                                <td>
                                    <span class="badge bg-primary text-white">
                                        Temp. Return
                                    </span>
                                </td>
                                @endif
                                <td>{{ $motorbike->year }}</td>
                                <td>{{ $motorbike->purchaseDate }}</td>
                                <td>$ {{ $motorbike->totalPurchasePrice }}.00</td>
                                <td>{{ $motorbike->plateNo }}</td>
                                <td>{{ $motorbike->motorColor }}</td>
                                <td>{{ $motorbike->engineNo }}</td>
                                <td>{{ $motorbike->chassisNo }}</td>
                                <td>{{ $motorbike->user->name }}</td>
                                <td class="justify-content-between ms-2 text-nowrap">
                                    <a href="{{ route('motorbikes.edit',$motorbike->motorID) }}" class="btn btn-primary btn-xs">Edit</a>
                                    <a href="{{ route('motorbikes.sold-stolen',$motorbike->motorID) }}" class="btn btn-danger btn-xs">Sold / Lost</a>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('motorbikes.destroy', $motorbike->motorID) }}" onsubmit="return confirm('Are you want to delete Motorbike No. {{ $motorbike->motorno }} !!!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary btn-xs">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <table class="table table-hover table-bordered text-nowrap mb-3">
                    <thead>
                        <tr>
                            <th>
                                @sortablelink('motorno', 'No.')
                            </th>
                            <th>
                                @sortablelink('motorType', 'Type')
                            </th>
                            <th>
                                @sortablelink('motorStatus', 'Status')
                            </th>
                            <th>
                                @sortablelink('year', 'Year')
                            </th>
                            <th>
                                @sortablelink('purchaseDate', 'Purchase At')
                            </th>
                            <th>
                                @sortablelink('plateNo', 'Plate No.')
                            </th>
                            <th>
                                @sortablelink('motorModel', 'Model')
                            </th>
                            <th>
                                @sortablelink('motorColor', 'Color')
                            </th>
                            <th>
                                @sortablelink('engineNo', 'Engine No.')
                            </th>
                            <th>
                                @sortablelink('chassisNo', 'Chassis No.')
                            </th>
                            <th>
                                @sortablelink('userID', 'Inputer')
                            </th>
                            <th class="text-primary">Actions</th>
                        </tr>
                    </thead>
                </table>
                <p class="text-center">No motorbikes found.</p>

            @endif
            </div>
            <!-- Basic Pagination -->
            <div class="demo-inline-spacing">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        @if ($motorbikes->currentPage() > 1)
                                <li class="page-item first">
                                    <a href="/motorbikes?page={{ $motorbikes->currentPage() - 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></a>
                                </li>
                            @endif

                                @for ($i = 1; $i <= $motorbikes->lastPage(); $i++)
                                    <li class="page-item {{ $motorbikes->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="/motorbikes?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                            @if ($motorbikes->currentPage() < $motorbikes->lastPage())
                                <li class="page-item last">
                                    <a href="/motorbikes?page={{ $motorbikes->currentPage() + 1 }}" class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></a>
                                </li>
                            @endif
                    </ul>
                </nav>
            </div>
            <!--/ Basic Pagination -->
        </div>
        </div>
</div>

<script>
    window.onbeforeunload = function() {
    localStorage.setItem('scrollPos', document.documentElement.scrollTop);
    };

    window.onload = function() {
    var scrollPos = localStorage.getItem('scrollPos');
    if (scrollPos) {
        window.scrollTo(0, scrollPos);
    }
    };

</script>

@endsection