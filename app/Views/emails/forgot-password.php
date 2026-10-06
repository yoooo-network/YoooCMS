<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background-color:#f8fafc; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:8px; overflow:hidden;">
                    <tr>
                        <td style="background-color:#2563eb; height:4px; line-height:4px; font-size:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:25px; text-align:center; background:#0f172a; color:#ffffff;">
                            <h1 style="margin:0; font-size:22px; letter-spacing: 1px;">Yooo.App</h1>
                            <p style="margin:5px 0 0; font-size:12px; color:#94a3b8; text-transform: uppercase;">Yooo Network</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px 30px; color:#1e293b;">
                            <h2 style="margin-top:0; font-size:20px; text-align:center; color:#0f172a;">Security Update</h2>
                            <p style="font-size:15px; line-height:1.5; color:#475569;"> 
                                Hello <?= esc($name ?? '') ?>,<br><br>
                                We received a request to update the access credentials for your account. You can set a new password by clicking the button below.
                            </p>
                            
                            <div style="text-align:center;"> 
                                <a href="<?= esc($reset_link ?? '') ?>" style="display:inline-block; margin-top:10px; padding:14px 32px; background:#2563eb; color:#ffffff; text-decoration:none; border-radius:5px; font-size:14px; font-weight:bold;"> Create New Password </a> 
                            </div>
                            
                            <div style="margin-top:30px; padding-top:20px; border-top:1px solid #e2e8f0;">
                                <p style="font-size:12px; color:#94a3b8; line-height:1.4;"> 
                                    For security, this link will expire shortly. If the button above does not work, please use this link:
                                </p>
                                <p style="word-break:break-all; font-size:11px; color:#2563eb;">
                                    <?= esc($reset_link ?? '') ?>
                                </p>
                            </div>
                            
                            <p style="margin-top:25px; font-size:11px; color:#94a3b8; text-align:center;"> 
                                If you did not initiate this request, you can safely disregard this message. Your account remain secure.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px; text-align:center; font-size:11px; color:#94a3b8; background:#f8fafc; border-top:1px solid #e2e8f0;">
                            <p style="margin:0;">&copy; 2026 Yooo.App</p>
                            <p style="margin:8px 0 0;"> 
                                <a href="#" style="color:#64748b; text-decoration:underline;">Unsubscribe</a> | 
                                <a href="#" style="color:#64748b; text-decoration:underline;">Help Center</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>