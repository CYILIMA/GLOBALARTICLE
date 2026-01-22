<!DOCTYPE html>
<html>
<head>
    <title>Breaking News Alert</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px; border-top: 4px solid #e63946;">
        <h2 style="color: #e63946; margin-top: 0;">🛑 BREAKING NEWS</h2>
        
        <h1 style="color: #333; font-size: 24px;">{{ $news->title }}</h1>
        
        @if($news->image)
            <img src="{{ asset('storage/' . $news->image) }}" alt="News Image" style="width: 100%; max-height: 300px; object-fit: cover; margin: 20px 0;">
        @endif
        
        <p>{{ Str::limit($news->content, 200) }}</p>
        
        <a href="{{ url('/') }}" style="display: inline-block; background: #0b3c74; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; border: none; font-weight: bold;">Read Full Article</a>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #999;">
            You received this email because you are subscribed to Global Articles Breaking News alerts.
            <a href="{{ url('/unsubscribe?email=' . urlencode($email)) }}" style="color: #999; text-decoration: underline;">Unsubscribe</a>
        </p>
    </div>
</body>
</html>
