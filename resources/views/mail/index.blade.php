<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your OTP Code - Elite Properties</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            padding: 30px;
            text-align: center;
        }

        .email-header img {
            max-width: 140px;
            transition: transform 0.3s ease;
        }

        .email-header img:hover {
            transform: scale(1.05);
        }

        .email-body {
            padding: 40px;
            text-align: center;
        }

        h2 {
            font-family: 'Lora', serif;
            color: #1e3a8a;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .otp-box {
            background-color: #eff6ff;
            color: #1e3a8a;
            font-size: 32px;
            font-weight: 600;
            text-align: center;
            padding: 20px;
            margin: 25px 0;
            border-radius: 12px;
            letter-spacing: 6px;
            border: 2px solid #3b82f6;
            transition: background-color 0.3s ease;
        }

        .otp-box:hover {
            background-color: #dbeafe;
        }

        .footer {
            background-color: #1e3a8a;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #d1d5db;
        }

        .footer a {
            color: #bfdbfe;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #ffffff;
        }

        @media screen and (max-width: 600px) {
            .email-wrapper {
                margin: 20px;
                border-radius: 12px;
            }

            .email-body {
                padding: 20px;
            }

            .otp-box {
                font-size: 28px;
                padding: 15px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-header">
            <img src="{{asset('storage/logo.png')}}" alt="MetroNic Logo">
        </div>
        <div class="email-body">
            <h2>Welcome to MetroNic!</h2>
            <p>Your journey to finding the perfect property starts here. To secure your account, please use the one-time
                password (OTP) below to complete your registration.</p>
            <div class="otp-box">{{ $otp }}</div>
            <p><strong>Important:</strong> This OTP is valid for a short time. For your security, do not share it with
                anyone.</p>
            <p>If you didn’t request this, feel free to ignore this email or contact our support team.</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} MetroNic. All rights reserved.<br>
        </div>
    </div>
</body>

</html>
