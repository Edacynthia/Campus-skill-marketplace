<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Approved</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:40px;">

    <div style="max-width:600px; margin:auto; background:white; border-radius:16px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.08);">

        <div style="background:#1e3a8a; color:white; padding:30px;">
            <h1 style="margin:0;">Campus Connect</h1>
            <p style="margin-top:10px; color:#dbeafe;">
                University Marketplace Platform
            </p>
        </div>

        <div style="padding:40px;">

            <h2 style="color:#111827;">
                Hello {{ $user->first_name }},
            </h2>

            <p style="color:#4b5563; line-height:1.7;">
                Great news! Your Campus Connect account has been approved by the administrator.
            </p>

            <p style="color:#4b5563; line-height:1.7;">
                You can now sign in and access all marketplace features including:
            </p>

            <ul style="color:#4b5563; line-height:1.8;">
                <li>Browse campus skills</li>
                <li>Post jobs</li>
                <li>Offer services</li>
                <li>Book trusted vendors</li>
            </ul>

            <div style="margin-top:35px;">
                <a href="{{ route('login') }}"
                   style="background:#1e3a8a; color:white; padding:14px 24px; text-decoration:none; border-radius:12px; font-weight:bold;">
                    Sign In to Campus Connect
                </a>
            </div>

            <p style="margin-top:40px; color:#6b7280; font-size:14px;">
                Thank you for being part of the Campus Connect community.
            </p>

        </div>

    </div>

</body>
</html>