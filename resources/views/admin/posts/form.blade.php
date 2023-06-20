@section('page_css')
<link href="{{URL::asset('back/css/select2.min.css')}}" rel="stylesheet" type="text/css">
@endsection
<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    {{Form::label('title', 'Post title *')}}
    {{Form::text("title", null, array("class"=>"form-control","id"=>"title"))}}
    @if ($errors->has('title'))
        <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
        </span>
    @endif
</div>

<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
    {{Form::label('content', 'Content *')}}

    {{Form::textarea("content", null, array("class"=>"form-control","id"=>"content"))}}
    @if ($errors->has('content'))
        <span class="help-block">
                <strong>{{ $errors->first('content') }}</strong>
        </span>
    @endif
</div>


<div class="form-group{{ $errors->has('featured_image') ? ' has-error ':''}}">
{{Form::label('featured_image','Featured image * ')}}
    {{Form::file('featured_image',null,["class"=>"form-control btn btn-default","id"=>"featured_image"])}}
        <img id="preview_featured_image" class="inputImgPreview" src="@if(isset($post)){{$post->featured_image->thumb}} @endif" class="img-thumbnail"/>
    @if ($errors->has('featured_image'))
        <span class="help-block">
            <strong>{{ $errors->first('featured_image') }}</strong>
        </span>
    @endif
</div>


{{-- <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
    <label>Active : </label>
    <label class="radio-inline">
        {{Form::radio('active','1',true,['id'=>'yes'])}} Yes
    </label>
    <label class="radio-inline">
        {{Form::radio('active','0',null,['id'=>'yes'])}} No
    </label>
    @if ($errors->has('active'))
        <span class="help-block">
                <strong>{{ $errors->first('active') }}</strong>
        </span>
    @endif
</div> --}}

@section('page_scripts')

    <script type="text/javascript" src="{{URL::asset('back/js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('back/js/select2.min.js')}}"></script>

    <script type="text/javascript">
        CKEDITOR.replace( 'content' );

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var targetPreview = 'preview_'+$(input).attr('id');
                reader.onload = function(e) {
                    $('#'+targetPreview).attr('src', e.target.result).show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#featured_image").change(function() {
            readURL(this);
        });
    </script>
@endsection

