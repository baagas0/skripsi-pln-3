<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <p>Yth. {{ $participant->employee->name }},</p>

        <p>Kami mengingatkan Anda untuk mengisi penilaian pelatihan yang telah Anda ikuti:</p>

        <p>
            Nama Pelatihan: {{ $diklat->name }}<br>
            Tanggal Pelaksanaan: {{ $diklat->name }}
        </p>

        <p>Penilaian pelatihan sangat penting untuk peningkatan mutu program kami ke depannya. 
        Mohon untuk segera mengisi formulir penilaian melalui tautan berikut: 
        <a href="{{ $url }}">Link Penilaian</a></p>

        <p>Terimakasih atas kerjasamanya.</p>

        <p>
        Kind Regards,<br>
        Tim HTD
        </p>
    </div>
</body>
</html>