@if(Session::has('success'))
	<div class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
		<h5>
			<i class="icon fa-solid fa-check"></i>
			Success!
		</h5>
		{{ Session::get('success') }}
	</div>
@elseif(Session::has('danger'))
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
		<h5>
			<i class="icon fa-solid fa-trash"></i>
			Deleted!
		</h5>
		{{ Session::get('danger') }}
	</div>
@elseif(Session::has('warning'))
	<div class="alert alert-warning alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
		<h5>
			<i class="icon fa-solid fa-exclamation-triangle"></i>
			Warning!
		</h5>
		{{ Session::get('warning') }}
	</div>
@elseif(Session::has('info'))
	<div class="alert alert-info alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
		<h5>
			<i class="icon fa-solid fa-info"></i>
			Updated!
		</h5>
		{{ Session::get('info') }}
	</div>
@endif


