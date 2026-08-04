<!DOCTYPE html>
<html>
<head>
    <title>Registration Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello, {{ $user->name }}!</h2>
    <p>We are sorry to inform you that your registration for <strong>{{ config('app.name') }}</strong> has been rejected.</p>
    
    @if($customMessage)
        <p><strong>Reason for rejection:</strong></p>
        <div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">
            {!! nl2br(e($customMessage)) !!}
        </div>
    @endif
    
    <p>If you believe this is a mistake, please contact our support team.</p>
    <br>
    <p>Best regards,</p>
    <p><strong>{{ config('app.name') }} Team</strong></p>
</body>
</html>
