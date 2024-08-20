<?php 
   session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="style/styles.css">
    <title>Login</title>
    <style>
    .logo-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin: 20px auto;
        display: block;
      }
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }
    .form-box {
        width: 500px;
        background-color: #f9f9f9;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
    .password-input {
        position: relative;
      }
    .password-input input[type="password"] {
        padding-right: 30px;
      }
    .password-input span {
        position: absolute;
        right: 10px;
        top: 0;
        transform: translateY(50%);
        cursor: pointer;
      }
      h2 {
        text-align: center;
      }
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
*{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Poppins',sans-serif;
}
body{
    background: #e4e9f7;
}
.container{
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 90vh;
}
.box{
    background: #fdfdfd;
    display: flex;
    flex-direction: column;
    padding: 25px 25px;
    border-radius: 20px;
    box-shadow: 0 0 128px 0 rgba(0,0,0,0.1),
                0 32px 64px -48px rgba(0,0,0,0.5);
}
.form-box{
    width: 450px;
    margin: 0px 10px;
}
.form-box header{
    font-size: 25px;
    font-weight: 600;
    padding-bottom: 10px;
    border-bottom: 1px solid #e6e6e6;
    margin-bottom: 10px;
}
.form-box form .field{
    display: flex;
    margin-bottom: 10px;
    flex-direction: column;

}
.form-box form .input input{
    height: 40px;
    width: 100%;
    font-size: 16px;
    padding: 0 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    outline: none;
}
.btn{
    height: 35px;
    background: rgba(76,68,182,0.808);
    border: 0;
    border-radius: 5px;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    transition: all .3s;
    margin-top: 10px;
    padding: 0px 10px;
}
.btn:hover{
    opacity: 0.82;
}
.submit{
    width: 100%;
}
.links{
    margin-bottom: 15px;
}
    </style>
</head>
<body>
      <div class="container">
        <div class="box form-box">
            <?php 
             
              if(isset($_POST['submit'])){
                $username = $_POST['email'];
                $password = $_POST['password'];

                if($username == "admin" && $password == "admin"){
                    $_SESSION['valid'] = true;
                    $_SESSION['username'] = $username;
                    header("Location: home.php");
                    exit;
                }else{
                    echo "<div class='message'>
                      <p>Wrong Username or Password</p>
                       </div> <br>";
                   echo "<a href='login.php'><button class='btn'>Go Back</button>";
                }
              }else{

            
          ?>
            <header>
    <img src="image/logo.png" alt="Logo" class="logo-image">
              <h2 style="text-align: center;">Login</h2>
            </header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Username</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input password-input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                    <span style="position: absolute; right: 1%; top: 70%; transform: translateY(-50%); cursor: pointer;"><i class="fa-solid fa-eye" id="eye"></i></span>
                </div>

                <div class="field">
                    
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
            </form>
        </div>
        <?php }?>
      </div>
      <script>
        let eye = document.getElementById("eye");
        let password = document.getElementById("password");
        eye.addEventListener("click", function(){
          if(password.type === "password"){
            password.type = "text";
            eye.className = "fa-solid fa-eye-slash";
          }else{
            password.type = "password";
            eye.className = "fa-solid fa-eye";
          }
        });
      </script>
</body>
</html>