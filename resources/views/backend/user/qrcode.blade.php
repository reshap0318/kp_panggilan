@extends('layouts.frontend')

@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{$user->first_name.' '.$user->last_name}} <small>Qr-Code</small></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
          <div class=" text-center">
            @if($user->QRpassword)
            <a href="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(38, 38, 38, 0.85)->backgroundColor(255, 255, 255, 0.82)->size(200)->errorCorrection('H')->generate($user->QRpassword)) !!}" download="QRCODE-{{$user->first_name}}"><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(38, 38, 38, 0.85)->backgroundColor(255, 255, 255, 0.82)->size(200)->errorCorrection('H')->generate($user->QRpassword)) !!} "></a>
              
              <p> This is your qr code , Download it into your mobile</p>
            @endif
            @if($pemilik=='saya')
              <button type="submit" id="autogenerate_qr" class="btn btn-default sub6">Change Now</button>
            @endif
          </div>
      </div>
    </div>
  </div>
</div>
@stop


@section('scripts')
  <script>
     //Delete Items
    $('#autogenerate_qr').click(function(){
        if(confirm('Are you sure you want to generate the qe code')){

           $.ajax({
              type: "POST",
              cache: false,
              url : "{{action('QrLoginController@QrAutoGenerate')}}",
              data: {action:'updateqr'},
                  success: function(data) {
                    if (data==1) {
                     location.reload();
                   }else{
                    alert( 'Ups error :P ');
                   }
                  }
              })

        
      }else{
          return false;
      }
    });
    //end qr autogenerated
  </script>
@endsection
