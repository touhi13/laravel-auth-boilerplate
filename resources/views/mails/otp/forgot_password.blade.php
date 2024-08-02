<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qbits Technologies Affiliate Program - OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        img {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<div class="container">
    <p>Hi,</p>
    <p>We received a request to reset your password for your AMZ Alert account.</p>
    <p>Your One-Time Password (OTP) for resetting your password is: <strong>{{ $otp }}</strong></p>
    <p>Please use this OTP to reset your password. If you haven't requested this password reset, please ignore
        this email.</p>
    <p>If you encounter any issues or have questions, feel free to reach out to our support team at <a href="mailto:support@myqbits.com">support@myqbits.com</a> or visit <a href="https://myqbits.com/contact">https://myqbits.com/contact</a></p>
    <p>For further information please visit our website: <a href="https://myqbits.com/">https://myqbits.com/</a></p>
    <p>Best regards,<br>Qbits Technologies</p>
    <img src="{{ asset('images/logo/qbits.png') }}" alt="Qbits Technologies Logo" />
    <p>Head Office: 793/1 Monipur, Mirpur 2, Dhaka 1216, Bangladesh <br>
        Service Point: 10th Floor, Mirpur Shopping Complex, Mirpur 2, Dhaka 1216, Bangladesh</p>
</div>
</body>


</html>
