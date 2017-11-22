
// login callback
function loginCallback(response) 
{
    console.log("here");
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