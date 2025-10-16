<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bevestig je inschrijving</title>
</head>
<body style="margin:0; padding:0; background-color:#f9f9f9; font-family: Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9f9f9; padding:20px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" border="0"
                   style="background-color:#ffffff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.05); overflow:hidden;">

                <tr>
                    <td style="background-color:#ffffff; padding:20px; text-align:center; border-bottom:4px solid #FAA21B;">
                        <img src="{{ asset('images/logo.svg') }}" alt="Covadis Logo" style="height:40px; width:auto;" />
                    </td>
                </tr>

                <tr>
                    <td style="padding:30px; color:#333;">
                        <h2 style="margin-top:0; font-size:22px; color:#FAA21B;">Hallo {{ $external->first_name }},</h2>

                        <p style="font-size:16px; line-height:1.5;">
                            Je hebt je ingeschreven voor <strong>{{ $activity->name }}</strong>.
                        </p>

                        <p style="font-size:16px; line-height:1.5;">
                            Klik op de knop hieronder om je inschrijving te bevestigen:
                        </p>

                        <p style="text-align:center; margin-top:30px;">
                            <a href="{{ route('activities.confirm', ['token' => $token]) }}"
                               style="display:inline-block; background-color:#FAA21B; color:white; padding:12px 24px; text-decoration:none; border-radius:5px; font-weight:bold; font-size:16px;">
                                Inschrijving bevestigen
                            </a>
                        </p>

                        <p style="font-size:14px; color:#666; text-align:center;">
                            Als je je niet hebt aangemeld voor deze activiteit, kun je deze e-mail negeren.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
