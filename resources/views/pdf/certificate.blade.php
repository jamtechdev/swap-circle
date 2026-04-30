<!DOCTYPE html>
<html>
<head>
    <style>
        body { text-align:center; font-family: Arial; }
        .box { border:5px solid #333; padding:40px; }
        h1 { font-size:28px; }
    </style>
</head>
<body>

<div class="box">
    <h1>Certificate</h1>

    <p>This is to certify that</p>

    <h2>{{ $beneficiary->first_name }} {{ $beneficiary->surname }}</h2>

    <p>is successfully covered under</p>

    <h3>{{ $product->name }}</h3>

    <p>Valid from {{ date('d-m-Y', strtotime($purchase->cover_start_date)) }}
    to {{ date('d-m-Y', strtotime($purchase->cover_end_date)) }}</p>

</div>

</body>
</html>