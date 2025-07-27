<!DOCTYPE html>
<html>

<head>
    <title>Pengingat Masa Berlaku Sertifikat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .header {
            text-align: center;
            padding: 10px;
            background-color: #f0f8ff;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .warning {
            color: #cc0000;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f0f8ff;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Pengingat Masa Berlaku Sertifikat</h2>
        </div>

        <p>Kepada Yth.</p>
        <p><strong>{{ $certificate->employee->name }}</strong> dan pihak terkait,</p>

        <p>Kami informasikan bahwa sertifikat Anda akan <span class="warning">berakhir dalam 2 bulan</span> dari
            sekarang.</p>

        <p>Detail sertifikat:</p>
        <table>
            <tr>
                <th>Nomor Sertifikat</th>
                <td>{{ $certificate->certificate_number }}</td>
            </tr>
            <tr>
                <th>Nama Pelatihan</th>
                <td>{{ $certificate->diklat->name }}</td>
            </tr>
            <tr>
                <th>Vendor</th>
                <td>{{ $certificate->vendor->name }}</td>
            </tr>
            <tr>
                <th>Tanggal Sertifikat</th>
                <td>{{ \Carbon\Carbon::parse($certificate->certificate_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Tanggal Kadaluarsa</th>
                <td class="warning">{{ \Carbon\Carbon::parse($certificate->certificate_expire)->format('d M Y') }}</td>
            </tr>
        </table>

        <p>Silakan hubungi departemen pelatihan untuk informasi perpanjangan atau pembaruan sertifikat.</p>

        <p>Terima kasih,</p>
        <p>Tim Diklat PLN</p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon untuk tidak membalas. Jika Anda memiliki pertanyaan, silakan
                hubungi administrator.</p>
        </div>
    </div>
</body>

</html>