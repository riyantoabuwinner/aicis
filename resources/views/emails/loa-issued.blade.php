<!DOCTYPE html>
<html>
<head>
    <title>Letter of Acceptance</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #17501b;">Congratulations! Your paper has been accepted.</h2>
        
        <p>Dear {{ $submission->author->name }},</p>
        
        <p>We are pleased to inform you that your submission titled "<strong>{{ $submission->title }}</strong>" has been accepted for the AICIS Conference.</p>
        
        <p>Please find attached the official <strong>Letter of Acceptance (LoA)</strong>. We look forward to your presentation and participation in the conference.</p>

        <p style="margin-top: 20px;">Please login to your dashboard for further details and to complete any remaining registration or payment steps.</p>
        
        <p>Warm regards,<br>AICIS Committee</p>
    </div>
</body>
</html>