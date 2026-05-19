@if (session()->has('success'))
  <div class="alert alert-success">
    <i class="glyphicon glyphicon-thumbs-up"></i>
    <button data-dismiss="alert" class="close">&times;</button>
    {{ session('success') }}
  </div>
@elseif (session()->has('danger'))
  <div class="alert alert-danger">
    <button data-dismiss="alert" class="close">&times;</button>
    {{ session('danger') }}
  </div>
@elseif (session()->has('warning'))
  <div class="alert alert-warning">
    <button data-dismiss="alert" class="close">&times;</button>
    {{ session('warning') }}
  </div>
@endif

{{-- validation errors --}}
@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br /><br />
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
{{-- /validation errors --}}
