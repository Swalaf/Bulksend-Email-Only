{{-- Campaign Completed Email Template --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Completed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .stats { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745; }
        .stat-item { display: inline-block; width: 30%; text-align: center; margin: 10px 1%; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Campaign Completed!</h1>
        <p>Your campaign "{{ $campaign->name }}" has finished sending</p>
    </div>

    <div class="content">
        <h2>Hi {{ $user->name }},</h2>

        <p>Great news! Your campaign "{{ $campaign->name }}" has completed successfully. All emails have been sent to your subscribers.</p>

        <div class="stats">
            <h3>Campaign Summary</h3>
            <div class="stat-item">
                <div style="font-size: 24px; font-weight: bold; color: #007bff;">{{ $campaign->subscribers_count ?? 0 }}</div>
                <div style="font-size: 12px; color: #666;">Emails Sent</div>
            </div>
            <div class="stat-item">
                <div style="font-size: 24px; font-weight: bold; color: #28a745;">{{ $campaign->analytics ? $campaign->analytics->opens : 0 }}</div>
                <div style="font-size: 12px; color: #666;">Opens</div>
            </div>
            <div class="stat-item">
                <div style="font-size: 24px; font-weight: bold; color: #ffc107;">{{ $campaign->analytics ? $campaign->analytics->clicks : 0 }}</div>
                <div style="font-size: 12px; color: #666;">Clicks</div>
            </div>
        </div>

        <p>You can now view detailed analytics and performance metrics in your dashboard.</p>

        <a href="{{ route('analytics.index') }}" class="button">View Analytics</a>

        <p>Want to create another campaign? Head to your dashboard to get started.</p>

        <a href="{{ route('campaigns.create') }}" class="button" style="background: #6c757d;">Create New Campaign</a>

        <p>Keep up the great work!<br>The BulkSend Team</p>
    </div>

    <div class="footer">
        <p>This email was sent to {{ $user->email }} to notify you of campaign completion.</p>
        <p>&copy; {{ date('Y') }} BulkSend. All rights reserved.</p>
    </div>
</body>
</html>