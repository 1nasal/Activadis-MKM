<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <title>Activeer je account</title>
</head>
<body style="margin:0; padding:0; background-color:#f9f9f9; font-family: Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9f9f9; padding:20px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.05); overflow:hidden;">
        <tr>
          <td style="background-color:#ffffff; padding:20px; text-align:center; border-bottom:4px solid #FAA21B;">
            <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }} Logo" style="height:40px; width:auto;" />
          </td>
        </tr>

        <tr>
          <td style="padding:30px; color:#333;">
            <h2 style="margin-top:0; font-size:22px; color:#FAA21B;">Hallo {{ $user->first_name }},</h2>

            <p style="font-size:16px; line-height:1.5;">
              Er is een account voor je aangemaakt op <strong>{{ config('app.name') }}</strong>. Klik op de knop hieronder om je account te activeren en een wachtwoord in te stellen.
            </p>

            <p style="text-align:center; margin-top:30px; margin-bottom:30px;">
              <a href="{{ $activationUrl }}"
                 style="display:inline-block; background-color:#FAA21B; color:#ffffff; padding:12px 24px; text-decoration:none; border-radius:5px; font-weight:bold; font-size:16px;">
                Activeer mijn account
              </a>
            </p>

            <p style="font-size:16px; line-height:1.5; margin-bottom:8px;">
              Werkt de knop niet? Kopieer en plak dan deze link in je browser:
            </p>
            <p style="font-size:14px; background-color:#f1f3f5; color:#333; padding:12px; border-radius:4px; word-break:break-all; margin-top:0; margin-bottom:24px;">
              {{ $activationUrl }}
            </p>

            <p style="font-size:14px; color:#666; text-align:center; margin-top:24px;">
              Deze activatielink is 48 uur geldig. Als je deze e-mail niet verwacht had, kun je deze negeren.
            </p>
          </td>
        </tr>

        <tr>
          <td style="padding:16px 30px 30px; text-align:center;">
            <p style="font-size:12px; color:#6c757d; margin:0;">
              © {{ date('Y') }} {{ config('app.name') }}. Alle rechten voorbehouden.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
