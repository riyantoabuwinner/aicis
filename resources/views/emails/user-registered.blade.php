<!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello, {{ $user->name }}!</h2>
    <p>Thank you for registering on our platform.</p>
    <p>Your account has been successfully created and is currently <strong>pending approval</strong> from our administrators.</p>
    <p>Please check your email periodically. You will receive another notification once your account has been approved and is ready to use.</p>
    <br>
    <p>Best regards,</p>
    <p><strong>{{ config('app.name') }} Team</strong></p>
</body>
</html>
