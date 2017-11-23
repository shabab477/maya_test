@extends('master')

@section('title', 'login')

@section("script")
    <script src="https://sdk.accountkit.com/en_US/sdk.js"></script>

    
<script>
    window.fbAsyncInit = function() {
        FB.init({
        appId      : '308652742954659',
        cookie     : true,
        xfbml      : true,
        version    : 'v2.8'
        });
        
        FB.AppEvents.logPageView();   
        
    };

    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });
    }

    function statusChangeCallback(response)
    {
        FB.api('/me', function(response) {
            $("#name").val(response.name);
            $("#user").submit();
        });
    }  

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

    <script>
    //doc says to put these code in the same file. 
    //Linked scripts doesn't work for some reason

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
            
            $("#code").val(code);
            $("#_token").val(csrf);
            $("#user").submit();
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

    function phoneLogin()
    {
        var val = $("#phone").val();
        if(isPhone(val))
        {
            console.log("calling phone");
            AccountKit.login(
                'PHONE', 
                {
                    countryCode: "+880", 
                    phoneNumber: val
                    
                },
                loginCallback
            );
        }
        else
        {
            $('#myModal').modal('show');
        }
    }


    function isPhone(phone)
    {
        var re = /^\d{10}$/;
        return re.test(phone);
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

<form id='user' method='POST' action='/user'>
    <input type='hidden' name='name' id='name'  />
    <input type='hidden' name='code' id='code'  />
    <input type="hidden" name='_token' id='_token'  value="{{ csrf_token() }}" />
</form>

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
                    <input onclick="smsLogin();" type="phone" class="form-control" id="phone" aria-describedby="phone number" placeholder="E.G: 167xxxxxxx (Please do not put 0 infront of your number)">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your phone number with anyone.</small>
                
                </div>
                
                  
                <button onclick="phoneLogin();" type="button" class="btn btn-primary center-block">Login</button>
               
            </form>

        </div>

    </div>

    <h2 class='option'><span>OR JUST LOGIN WITH FACEBOOK</span></h2>
    <div class='row'>
        <div class='center-block col-md-2'>
            <fb:login-button  size="medium" scope="public_profile,email" onlogin="checkLoginState();">
                Login Using your Facebook Profile
            </fb:login-button>
        </div>
    </div>
    <div id="status">
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