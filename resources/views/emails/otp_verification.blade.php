<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            background-color: #f4f7fc;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .email-content {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        .email-header {
            font-size: 24px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 20px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #007BFF;
            padding: 10px 20px;
            background-color: #e6f4ff;
            border-radius: 5px;
            margin: 20px 0;
        }
        .instructions {
            font-size: 16px;
            color: #555555;
            margin-bottom: 30px;
        }
        .footer {
            font-size: 12px;
            color: #888888;
        }
        .footer a {
            color: #1a73e8;
            text-decoration: none;
        }
        .footer p {
            margin: 10px 0 0;
        }
        .button {
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            text-align: center;
            border-radius: 5px;
            display: inline-block;
            text-decoration: none;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="email-content">
            <h2 class="email-header">Email Verification OTP</h2>
            <p class="instructions">Dear {{ $userName }},</p>
            <p class="instructions">Thank you for registering with us. Please use the One-Time Password (OTP) below to verify your email address and complete the registration process:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p class="instructions">The OTP is valid for 10 minutes. If you did not request this, please disregard this email.</p>
            <div class="footer">
                <p>If you did not initiate this request, you can safely ignore this email.</p>
                <p><a href="#">Privacy Policy</a> | <a href="#">Contact Support</a></p>
            </div>
        </div>
    </div>

</body>
</html>
