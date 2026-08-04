<!DOCTYPE html>
<html>
<head>
    <title>Status Update for Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #17501b;">AICIS Submission Update</h2>
        
        <p>Dear {{ $submission->author->name }},</p>
        
        <p>The status of your submission titled "<strong>{{ $submission->title }}</strong>" has been updated.</p>
        
        <p>Current Status: <span style="background-color: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-weight: bold;">{{ $submission->status }}</span></p>

        @if($submission->validation_notes)
            <div style="margin-top: 20px; padding: 15px; border-left: 4px solid #17501b; background-color: #f8fafc;">
                <strong>Notes / Instructions from Reviewer:</strong>
                <p>{{ $submission->validation_notes }}</p>
            </div>
        @endif

        <p style="margin-top: 20px;">Please login to your dashboard for more details.</p>
        
        <p>Thank you,<br>AICIS Committee</p>
    </div>
</body>
</html>