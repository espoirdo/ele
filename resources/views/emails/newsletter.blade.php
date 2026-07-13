<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sujet }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin:0; padding:0; background:#f5f5f5; font-family:'Poppins', Arial, sans-serif; color:#333; }
        .wrapper { width:100%; background:#f5f5f5; padding:24px 0; }
        .card { max-width:640px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; }
        .header { background:linear-gradient(90deg, #CC0000 0%, #910000 100%); padding:28px 24px; text-align:center; }
        .logo { color:#ffffff; font-size:28px; font-weight:800; letter-spacing:1px; }
        .content { padding:28px 24px; font-size:15px; line-height:1.8; color:#333333; }
        .footer { padding:0 24px 24px; font-size:12px; color:#777777; text-align:center; }
        .unsubscribe { color:#CC0000; text-decoration:none; }
        .copyright { font-family:'Agency FB', Arial, sans-serif; font-size:14px; margin-top:8px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="header">
            <div class="logo">ELEDJI</div>
        </div>
        <div class="content">
            {!! nl2br(e($contenu)) !!}
        </div>
        <div class="footer">
            <p>Si vous ne souhaitez plus recevoir nos emails, <a class="unsubscribe" href="https://eledji.page.gd/newsletter/desabonnement?email={{ urlencode($email) }}">désabonnez-vous ici</a>.</p>
            <p class="copyright">© Eledji</p>
        </div>
    </div>
</div>
</body>
</html>
