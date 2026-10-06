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
                            <h2 style="margin-top:0; font-size:20px; text-align:center; color:#0f172a;">Account Setup Guide</h2>
                            <p style="font-size:15px; line-height:1.5; color:#475569;"> 
                                Hello <?= esc($name ?? '') ?>,<br><br>
                                Follow this checklist to finalize your listing and submit it for professional review:
                            </p>
                            
                            <div style="font-size:14px; line-height:1.6; color:#475569;">
                                <p><strong>1. Access Dashboard:</strong> Sign in to your secure account area.</p>
                                
                                <p><strong>2. Profile Essentials:</strong> 
                                <br><span style="font-size:12px; color:#64748b;">Enter your full name, birth date, and primary service location. Include a brief bio regarding your interests and professional background.</span></p>
                                
                                <p><strong>3. Identity & Preferences:</strong> 
                                <br><span style="font-size:12px; color:#64748b;">Select your gender identity and orientation. Specify your preferred client demographics to ensure compatible matches.</span></p>
                                
                                <p><strong>4. Personal Specs:</strong> 
                                <br><span style="font-size:12px; color:#64748b;">Detail your physical attributes, including height, build, and ethnicity, for accurate directory categorization.</span></p>
                                
                                <p><strong>5. Contact Channels:</strong> 
                                <br><span style="font-size:12px; color:#64748b;">Provide your mandatory mobile number and any optional social platforms (WhatsApp, Telegram, etc.) for client inquiries.</span></p>
                                
                                <p><strong>6. Service Rates & Portfolio:</strong> 
                                <br><span style="font-size:12px; color:#64748b;">Define your hourly and overnight rates. Select the specific services you offer and upload 3-4 high-quality images to increase engagement.</span></p>
                                
                                <p><strong>7. Final Submission:</strong> 
                                <br><span style="font-size:12px; color:#64748b;">Review the network terms and submit your profile for activation.</span></p>
                            </div>

                            <div style="text-align:center;"> 
                                <a href="<?= esc($login_link ?? '') ?>" style="display:inline-block; margin-top:25px; padding:14px 32px; background:#2563eb; color:#ffffff; text-decoration:none; border-radius:5px; font-size:14px; font-weight:bold;"> Sign in </a> 
                            </div>
                            
                            <p style="margin-top:30px; font-size:11px; color:#94a3b8; text-align:center; line-height:1.5;"> 
                                Standard review time is typically between 30 and 60 minutes.
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