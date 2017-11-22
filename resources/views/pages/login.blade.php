@extends('master')

@section('title', 'login')

@section("script")
    <script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
    <script>
    AccountKit_OnInteractive = function(){
        AccountKit.init(
        {
            appId:"308652742954659", 
            state: $("#token").val(), 
            version:"v1.1",
            debug: true
        }
        
        );
        console.log($("#token").val());
    }

    function loginCallback(response) 
    {
        console.log(response);
        if (response.status === "PARTIALLY_AUTHENTICATED") {
            var code = response.code;
            var csrf = response.state;
            
            // Send code to server to exchange for access token
        }
        else if (response.status === "NOT_AUTHENTICATED") {
            $('#myModal').modal('show');
        }
        else if (response.status === "BAD_PARAMS") {
            $('#myModal').modal('show');
        }
    }



    function emailLogin()
    {
        var val = $("#email").val();
        if(isEmail(val))
        {
            console.log("calling email");
            AccountKit.login(
            'EMAIL',
            {emailAddress: val},
            loginCallback
            );
        }
        else
        {
            $('#myModal').modal('show');
        }
    }

    function isEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }


    </script>
    
   

@endsection

@section("style")

    <link rel="stylesheet" href="{{ URL::to('css/login.css') }}" />
      
@endsection

@section('content')

<div class="container">


<h2>Login</h2>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#email_log">Login By Email</a></li>
        <li><a data-toggle="tab" href="#phone_log">Login By Phone</a></li>
    </ul>

    <div class="tab-content">
        <div id="email_log" class="tab-pane fade in active">
            <form>
                <input type='hidden' name='code' id='code'  />
                <div class="form-group col-md-12">
                    <label for="email">Email address</label>
                    <input type="email" name='email' class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone.</small>
                </div>
                

                <button onclick="emailLogin();" type="button" class="btn btn-primary center-block">Login</button>
            </form>

        </div>

        
        <div id="phone_log" class="tab-pane fade">
            <form>
                <input type='hidden' name='token' id='token' value="{{ csrf_token() }}" />
                <input type='hidden' name='code' id='code'  />
                <div class="form-group col-md-3">
                    <label for="country">Country Code</label>
                    <input type="text" class="form-control" id="country" aria-describedby="country number" value="+880" disabled>
                </div>
                <div class="form-group col-md-9">
                    <label for="phone">Phone Number</label>
                    <input onclick="smsLogin();" type="phone" class="form-control" id="phone" aria-describedby="phone number" placeholder="Enter phone number">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your phone number with anyone.</small>
                
                </div>
                
                  
                <button type="submit" class="btn btn-primary center-block">Login</button>
               
            </form>

        </div>

    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ERROR!!</h4>
        </div>
        <div class="modal-body">
          <p>PLease enter valid email or phone number.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>

</div>

@endsection