{{-- Purchase Confirmation Email Template --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .purchase-details { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border: 1px solid #dee2e6; }
        .credentials { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; font-family: monospace; border: 1px solid #dee2e6; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Purchase Confirmed!</h1>
        <p>Your SMTP credentials are ready</p>
    </div>

    <div class="content">
        <h2>Hi {{ $user->name }},</h2>

        <p>Thank you for your purchase! Your SMTP credentials have been successfully activated and are ready to use.</p>

        <div class="purchase-details">
            <h3>Purchase Details</h3>
            <p><strong>Listing:</strong> {{ $purchase->marketplaceListing->title }}</p>
            <p><strong>Price:</strong> ${{ number_format($purchase->amount, 2) }}</p>
            <p><strong>Purchase Date:</strong> {{ $purchase->created_at->format('M j, Y g:i A') }}</p>
            <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">Active</span></p>
        </div>

        <div class="credentials">
            <h4>SMTP Credentials</h4>
            <p><strong>Host:</strong> {{ $purchase->marketplaceListing->smtp_host }}</p>
            <p><strong>Port:</strong> {{ $purchase->marketplaceListing->smtp_port }}</p>
            <p><strong>Username:</strong> {{ $purchase->marketplaceListing->smtp_username }}</p>
            <p><strong>Password:</strong> {{ $purchase->marketplaceListing->smtp_password }}</p>
            <p><strong>Encryption:</strong> {{ $purchase->marketplaceListing->smtp_encryption ?? 'None' }}</p>
            <p><strong>From Email:</strong> {{ $purchase->marketplaceListing->from_email }}</p>
            <p><strong>From Name:</strong> {{ $purchase->marketplaceListing->from_name }}</p>
        </div>

        <div class="warning">
            <strong>Important:</strong> Please save these credentials securely. For security reasons, we recommend adding them to your SMTP accounts immediately and then deleting this email.
        </div>

        <p>You can now use these credentials in your campaigns. Head to your dashboard to set them up.</p>

        <a href="{{ route('smtp.create') }}" class="button">Add to SMTP Accounts</a>

        <p>If you have any questions about your purchase or need help setting up the credentials, don't hesitate to contact our support team.</p>

        <p>Happy sending!<br>The BulkSend Team</p>
    </div>

    <div class="footer">
        <p>This email was sent to {{ $user->email }} to confirm your purchase.</p>
        <p>&copy; {{ date('Y') }} BulkSend. All rights reserved.</p>
    </div>
</body>
</html>