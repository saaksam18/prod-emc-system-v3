{{-- Message --}}
@if (session()->has('success'))
<div class="col-lg-12">
    <div class="alert alert-primary " role="alert">
        {{ session()->get('success') }}
    </div>
</div>
@endif
@if (count($errors) > 0)<div class="col-lg-12">
    <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <span class="badge bg-danger text-white" onclick="this.parentElement.style.display='none';">x</span>
        <br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
{{-- End Message --}}