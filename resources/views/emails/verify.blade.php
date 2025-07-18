<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
<h1>Email Verification</h1>
<p>Thank you for registering with Profitrefer. To complete your sign-up, please verify your email by entering the following code: </p>
<p style="font-weight:bold;">{{ $user->verification_code }}</p>
<p>If you did not request this, please ignore this email.</p>
<br>
<p>Best Regards,</p>
<p>Profitrefer Team</p>
</body>
</html>
