<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div style="padding:80px;">
    <hr>
    <div style="display:block; text-align:center;">
      <p style="font-size:25px;">Password reset successfully!</p>
      <p style="font-size:16px; text-align:center; margin-top:25px">We have successfully reset the password for logging in to the website.
        If you want to use your password You can take this password. go to login
        and able to change the password.</p>
      <p style="font-size:16px; text-align:center;">Please keep this password secret. for security to change the password.</p>
      <div style="background-color:#F9F9F9; text-align:center; width:100%; padding:15px; margin-top:5px;">
        <p style="font-size:16px; color:#898989; margin-bottom:0px; margin-top:5px;">Username</p>
        <p style="font-size:16px; margin-bottom:0px; margin-top:5px;"><?php echo $params['username'] ?></p>
        <p style="font-size:16px; color:#898989; margin-bottom:0px; margin-top:15px;">Password</p>
        <p style="font-size:16px; margin-top:5px;"><?php echo $params['new_password'] ?></p>
      </div>
      <p style="margin-top:80px; color:#898989; margin-bottom:0px;">You will be able to access the website by copying your login details.
        and the password that we specify to log in immediately.</p>
    </div>
  </div>
</body>
</html>