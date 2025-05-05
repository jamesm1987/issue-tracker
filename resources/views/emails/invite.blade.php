<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite</title>
</head>
<body>
    <h1>Hello, {{ $invite->email }}</h1>

    <p>You have been invited to join.</p>

    <p>Click the link below to accept the invite and join us:</p>
    <a href="#">Accept Invite</a>
    
    <p>Best regards,</p>
    <p>{{ config('app.name') }} Team</p>
</body>
</html>