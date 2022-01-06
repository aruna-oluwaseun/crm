<table style="width:100%;max-width:620px;margin:0 auto;">
    <tbody>
    <tr>
        <td style="text-align: center; padding:25px 20px 0;">
            <p style="font-size: 13px;">Copyright © {{ date('Y') }} {{ env('COMPANY_NAME','Jenflow Systems Ltd') }}. All rights reserved. <br> Visit us <a style="color: #fd4c1c; text-decoration:none;" href="{{ env('APP_URL') }}">{{ env('COMPANY_NAME','Jenflow Systems Ltd') }}</a>.</p>
            {{ view('emails.templates.socials') }}
            <p style="padding-top: 15px; font-size: 12px;">This email was sent to you as a registered user of <a style="color: #fd4c1c; text-decoration:none;" href="{{ env('APP_URL') }}">{{ env('APP_URL') }}</a>. To update your emails preferences <a style="color: #fd4c1c; text-decoration:none;" href="{{ env('APP_URL') }}">click here</a>.</p>
        </td>
    </tr>
    </tbody>
</table>
