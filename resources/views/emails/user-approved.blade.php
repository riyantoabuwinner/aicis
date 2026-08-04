<!DOCTYPE html>
<html>
<head>
    <title>Account Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello, {{ $user->name }}!</h2>
    <p>Good news! Your account has been reviewed and <strong>approved</strong> by our administrators.</p>
    
    @if($customMessage)
        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #1b5e20; margin: 20px 0;">
            {!! nl2br(e($customMessage)) !!}
        </div>
    @else
        <p>You can now log in and access the full features of the platform.</p>
    @endif
    
    <p><a href="{{ url('/admin/login') }}" style="display: inline-block; padding: 10px 20px; background-color: #1b5e20; color: #fff; text-decoration: none; border-radius: 5px;">Login Now</a></p>
    <br>
    <p>Best regards,</p>
    <p><strong>{{ config('app.name') }} Team</strong></p>
</body>
</html>
