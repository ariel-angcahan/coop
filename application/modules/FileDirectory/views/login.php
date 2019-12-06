<!DOCTYPE html>
<html>
<head>
    <title>File Directory Sample</title>
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<input type="hidden" name="token" id="token" value="<?php echo $generated_token; ?>">
<style type="text/css">

    .user-login{
        border: 1px solid black;
        width: 100%;
        height: 100px;
    }

    .input-form{
        padding: 5px;
        width: 100%;
    }
</style>
<body>
    <h2>Authenticate</h2>
    <div class="user-login">
        <div class="input-form">
            <label>Username: </label><input type="text" name="username" id="username" placeholder="Username">
        </div>
        <div class="input-form">
            <label>Password: </label><input type="password" name="password" id="password" placeholder="Password">
        </div>
        <div class="btn-login">
            <button id="btn-login">Login</button>
        </div>
    </div>
</body>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

<script type="text/javascript">
    $(document).on('click','#btn-login', function(){
        var form_data = $(this).serialize()+"&" +$.param({
            username: $('#username').val(),
            password: $('#password').val(),
            token: $('#token').val()
        });

        $.when(request('FileDirectory/authenticate', form_data)).done(function(response){
            $('#token').val(response.generated_token);
            if(response.success){
                location.reload();
            }else{
               alert(`Warning: ${response.msg}`);
            }
        });
    });

    function request(url, data, global_ = true, dataType = "json"){
        return $.ajax({
            type: "POST",
            url: url,
            dataType: dataType,
            data: data,
            global: global_,
            error: function(jqXHR, lsStatus, ex){
                switch(jqXHR.status){
                    case 404:
                        alert(`${"System Location"}, ${"Page not found!"}`);
                    break;
                    case 403:
                        alert(`${"Timeout"}, ${"Session not found, you will redirecting to main page!"}`);
                    break;
                    case 500: 
                        alert(`${"System Error!"}, ${"Please contact your administrator!"}`);
                    break;
                }
            }
        });
    }
</script>
</html>