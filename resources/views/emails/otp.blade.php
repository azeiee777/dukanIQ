<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Verification Code</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }

        .container {
            background-color: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #6366f1;
            margin-bottom: 10px;
        }

        .otp-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            font-family: 'Courier New', monospace;
        }

        .message {
            text-align: center;
            margin: 20px 0;
            color: #64748b;
        }

        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #92400e;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }
    </style>
</head>

<body>
    @php
        $isLoginOtp = $otp->purpose === \App\Models\Otp::PURPOSE_LOGIN;
    @endphp

    <div class="container">
        <div class="header">
            <div class="logo">Dukaniq</div>
            <h1 style="margin: 0; color: #1f2937;">
                {{ $isLoginOtp ? 'Your Login Code' : 'Verify Your Email' }}
            </h1>
        </div>

        <div class="message">
            <p>Hello!</p>
            <p>
                {{ $isLoginOtp
                    ? 'Use the code below to sign in to your Dukaniq account.'
                    : 'Thank you for signing up with Dukaniq. To complete your registration, please use the verification code below:' }}
            </p>
        </div>

        <div class="otp-code">
            {{ $otp->otp }}
        </div>

        <div class="warning">
            <strong>Important:</strong> This code will expire in 10 minutes.
            {{ $isLoginOtp ? 'Please use it immediately to finish logging in.' : 'Please use it immediately to verify your email address.' }}
        </div>

        <div class="message">
            <p>If you didn't request this verification code, please ignore this email.</p>
        </div>

        <div class="footer">
            <p>This email was sent to {{ $otp->email }}</p>
            <p>&copy; 2026 Dukaniq. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
