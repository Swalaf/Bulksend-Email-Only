{{-- Welcome Email Template --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BulkSend</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to BulkSend!</h1>
        <p>Your email marketing journey starts here</p>
    </div>

    <div class="content">
        <h2>Hi {{ $user->name }},</h2>

        <p>Welcome to BulkSend! We're excited to have you join our community of successful email marketers.</p>

        <p>Here's what you can do to get started:</p>

        <ul>
            <li><strong>Set up your SMTP accounts</strong> - Connect your email service providers</li>
            <li><strong>Import your subscribers</strong> - Upload your contact lists</li>
            <li><strong>Create your first campaign</strong> - Send beautiful emails to your audience</li>
            <li><strong>Track performance</strong> - Monitor opens, clicks, and conversions</li>
        </ul>

        <p>If you need any help, our documentation is available 24/7, or you can reach out to our support team.</p>

        <a href="{{ url('/dashboard') }}" class="button">Get Started</a>

        <p>Happy sending!<br>The BulkSend Team</p>
    </div>

    <div class="footer">
        <p>This email was sent to {{ $user->email }} because you signed up for BulkSend.</p>
        <p>If you didn't create this account, please ignore this email.</p>
        <p>&copy; {{ date('Y') }} BulkSend. All rights reserved.</p>
    </div>
</body>
</html>