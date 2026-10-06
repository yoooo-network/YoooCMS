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
                            <h2 style="margin-top:0; font-size:20px; text-align:center; color:#0f172a;">Verification Completed</h2>
                            <p style="font-size:15px; line-height:1.5; color:#475569;"> 
                                Hello <?= esc($name ?? '') ?>,<br><br>
                                Your submission has been reviewed and approved. Your identity status is now fully verified.
                            </p>
                            
                            <div style="background:#f0f9ff; border:1px solid #bae6fd; padding:20px; border-radius:6px; margin:25px 0; text-align:center;">
                                <p style="margin:0; font-size:14px; color:#0369a1; font-weight:bold;"> 
                                    ✓ Trust Authentication Added
                                </p>
                            </div>

                            <div style="text-align:center;"> 
                                <a href="<?= esc($profile_url ?? '') ?>" style="display:inline-block; padding:14px 32px; background:#2563eb; color:#ffffff; text-decoration:none; border-radius:5px; font-size:14px; font-weight:bold;"> View My Updated Profile </a> 
                            </div>
                            
                            <p style="margin-top:30px; font-size:12px; color:#94a3b8; text-align:center; line-height:1.5;"> 
                                Verified listings build professional credibility and help maximize your presence on the network.
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