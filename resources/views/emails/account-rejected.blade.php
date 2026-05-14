<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:40px;">

<div style="max-width:600px; margin:auto; background:white; border-radius:16px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.08);">

    <div style="background:#991b1b; color:white; padding:30px;">
        <h1 style="margin:0;">Campus Connect</h1>
        <p style="margin-top:10px; color:#fecaca;">
            University Marketplace Platform
        </p>
    </div>

    <div style="padding:40px;">

        <h2 style="color:#111827;">
            Hello {{ $user->first_name }},
        </h2>

        <p style="color:#4b5563; line-height:1.7;">
            We appreciate your interest in Campus Connect.
        </p>

        <p style="color:#4b5563; line-height:1.7;">
            Unfortunately, your account request was not approved by the administrator at this time.
        </p>

        <p style="color:#4b5563; line-height:1.7;">
            Campus Connect is currently restricted to approved university community members only.
        </p>

        <div style="margin-top:30px; padding:20px; background:#fef2f2; border-radius:12px;">
            <p style="margin:0; color:#991b1b; font-size:14px;">
                If you believe this was a mistake, please contact the university administrator.
            </p>
        </div>

        <p style="margin-top:40px; color:#6b7280; font-size:14px;">
            Thank you for your understanding.
        </p>

    </div>

</div>

</body>
</html>