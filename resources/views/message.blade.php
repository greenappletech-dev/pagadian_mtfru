<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
</head>
<style>

    body {
        height: 100vh;
        background: linear-gradient(rgba(0, 0, 0, 0.3),rgba(0, 0, 0, 0.5)) , url('public/image/background/pagadian.png') no-repeat center center fixed;
        background-size: cover;
    }

</style>


<body>

<div class="message" style="position: absolute; top: 50%; left: 50%; transform:translate(-50%, -50%); width: 400px;">
    <div class="message-content" style="text-align: justify; color: #000; font-size: 18px; background: rgba(237, 237, 237, 0.9); padding: 30px; border-radius: 5px; box-shadow: 2px 2px 5px 2px rgba(255, 120, 31, 1)">
        <div class="business-icons text-center mb-5">
            <img src="{{ asset('smartservelogo.jpg') }}" style="width: 300px;">
        </div>

        <p>Thank you for submitting your New Business Registration. We will review your application and once verified we will email you your portal credentials so you can finished your application.</p>
        <a href="{{ route('login') }}" style="font-size: 14px;">Return to LogIn</a>
    </div>
</div>

</body>
</html>
