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

    .file-directory{
        width: 100%;
    }

    .input-form{
        padding: 5px;
        width: 100%;
    }
</style>
<body>
    <a href="FileDirectory/logout">Logout</a>
    <div class="file-directory">
        <h2>Files from Users Directory</h2>
        <h5>User ID: <?php echo $id; ?></h5>
        <h5>Username: <?php echo $username; ?></h5>
        <h5>NAME: <?php echo $name; ?></h5>
        <h5>Is Login: <?php echo $isLogin ? "True" : "False"; ?></h5>
        <table id="table-file-directory" border="1">
            <thead>
                <th>#</th>
                <th>FILE NAME</th>
                <th>PATH</th>
                <th></th>
            </thead>
            <tbody>
                <?php echo $list; ?>
            </tbody>
        </table>
    </div>
</body>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

<script type="text/javascript">
    $(document).on('click','#btn-login', function(){
        var form_data = $(this).serialize()+"&" +$.param({
            username: $('#username').val(),
            password: $('#password').val()
            token: $('#token').val()
        });

        $.when(request('FileDirectory/authenticate', form_data)).done(function(response){
            $('#token').val(response.generated_token);
            if(response.success){
            }else{
               alert(`Warning, ${response.msg}`);
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