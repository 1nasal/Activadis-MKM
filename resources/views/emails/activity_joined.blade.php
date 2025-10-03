<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bevestiging deelname</title>
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
                        <h2 style="margin-top:0; font-size:22px; color:#FAA21B;">Hallo {{ $name }},</h2>
                        <p style="font-size:16px; line-height:1.5;">
                            Bedankt voor je aanmelding bij de activiteit <strong>{{ $activity->name }}</strong>.
                        </p>

                        <p style="font-size:16px; line-height:1.5;">
                            <strong>Wanneer:</strong> {{ $activity->start_time->format('d-m-Y H:i') }}
                            @if($activity->end_time) tot {{ $activity->end_time->format('d-m-Y H:i') }} @endif
                        </p>

                        <p style="font-size:16px; line-height:1.5;">
                            <strong>Locatie:</strong> {{ $activity->location }}
                        </p>

                        <p style="font-size:16px; line-height:1.5;">
                            We kijken ernaar uit je daar te zien!
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
