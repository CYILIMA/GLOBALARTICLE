<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Global Articles</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;">
        <h2 style="color: #0b3c74;">Welcome to Global Articles!</h2>
        <p>Thank you for subscribing to our newsletter.</p>
        <p>You will now be the first to know about breaking news, in-depth analysis, and updates from around the globe.</p>
        
        <p>If you did not sign up for this account, you can <a href="{{ url('/unsubscribe?email=' . urlencode($email)) }}" style="color: #e63946;">unsubscribe immediately</a>.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #999;">
            &copy; {{ date('Y') }} Global Articles. All rights reserved.
        </p>
    </div>
</body>
</html>
