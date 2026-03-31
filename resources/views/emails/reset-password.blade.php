{{-- Password Reset Email Template --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Password Reset</h1>
        <p>Reset your BulkSend password</p>
    </div>

    <div class="content">
        <h2>Hi {{ $user->name }},</h2>

        <p>You recently requested to reset your password for your BulkSend account. Click the button below to reset it.</p>

        <a href="{{ $resetUrl }}" class="button">Reset Password</a>

        <p>This password reset link will expire in 60 minutes for security reasons.</p>

        <div class="warning">
            <strong>Didn't request this?</strong><br>
            If you didn't request a password reset, please ignore this email. Your password will remain unchanged.
        </div>

        <p>If the button above doesn't work, copy and paste this link into your browser:</p>
        <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>

        <p>Best regards,<br>The BulkSend Team</p>
    </div>

    <div class="footer">
        <p>This email was sent to {{ $user->email }} because a password reset was requested.</p>
        <p>&copy; {{ date('Y') }} BulkSend. All rights reserved.</p>
    </div>
</body>
</html>