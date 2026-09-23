<?php
session_start();

$otp_sent = false;
$fixed_otp = '123456';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Send OTP
    if (isset($_POST['phone_number'])) {
        $_SESSION['otp'] = $fixed_otp;
        $_SESSION['phone_number'] = $_POST['phone_number'];
        $otp_sent = true;
    }

    // Verify OTP
    if (isset($_POST['otp'])) {

        $otp_sent = true;

        if ($_POST['otp'] === $_SESSION['otp']) {

            $_SESSION['logged_in'] = true;

            header("Location: index.php");
            exit();

        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>DAMTS Login</title>

<style>
body{
height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
font-family:Segoe UI;
margin:0;
}

.login-container{
width:380px;
padding:30px;
border-radius:16px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(15px);
box-shadow:0 10px 25px rgba(0,0,0,0.4);
border:1px solid rgba(255,255,255,0.1);
}

h2{
text-align:center;
color:#00bfff;
margin-bottom:25px;
}

label{
color:#ddd;
}

input[type="text"]{
width:94%;
padding:12px;
border-radius:30px;
border:none;
margin-top:8px;
margin-bottom:20px;
background:rgba(255,255,255,0.15);
color:#fff;
outline:none;
}

input[type="submit"]{
width:100%;
padding:12px;
border-radius:30px;
border:none;
background:linear-gradient(90deg,#0088CC,#00bfff);
color:white;
cursor:pointer;
}

.otp-container{
display:flex;
justify-content:center;
gap:10px;
margin:20px 0;
}

.otp-box{
width:45px;
height:45px;
text-align:center;
font-size:20px;
border-radius:10px;
border:none;
background:rgba(255,255,255,0.15);
color:white;
}

.message{
color:#ff4d4d;
text-align:center;
margin-top:10px;
}
</style>
</head>

<body>

<div class="login-container">

<h2>DAMTS LOGIN</h2>

<?php if (!$otp_sent): ?>

<form method="POST">
<label>Phone Number</label>
<input type="text" name="phone_number" placeholder="Enter phone number" required>
<input type="submit" value="Send OTP">
</form>

<?php else: ?>

<form method="POST">

<p style="text-align:center;color:white;">
OTP sent to <b><?php echo $_SESSION['phone_number']; ?></b>
</p>

<div class="otp-container">
<input type="text" maxlength="1" class="otp-box">
<input type="text" maxlength="1" class="otp-box">
<input type="text" maxlength="1" class="otp-box">
<input type="text" maxlength="1" class="otp-box">
<input type="text" maxlength="1" class="otp-box">
<input type="text" maxlength="1" class="otp-box">
</div>

<input type="hidden" name="otp" id="finalOtp">

<input type="submit" value="Verify OTP">

</form>

<?php endif; ?>

<?php
if(isset($_POST['otp']) && $_POST['otp'] !== $_SESSION['otp']){
    echo '<div class="message">Incorrect OTP. Please try again.</div>';
}
?>

</div>

<script>
const otpInputs = document.querySelectorAll(".otp-box");
const finalOtp = document.getElementById("finalOtp");

otpInputs.forEach((input,index)=>{

    input.addEventListener("input",()=>{

        if(input.value.length === 1 && index < otpInputs.length - 1){
            otpInputs[index+1].focus();
        }

        updateOTP();
    });

    input.addEventListener("keydown",(e)=>{

        if(e.key === "Backspace" && input.value === "" && index > 0){
            otpInputs[index-1].focus();
        }

    });

});

function updateOTP(){

    let otp = "";

    otpInputs.forEach(box=>{
        otp += box.value;
    });

    if(finalOtp){
        finalOtp.value = otp;
    }
}
</script>

</body>
</html>