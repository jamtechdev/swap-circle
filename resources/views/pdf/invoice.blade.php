<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #333; background: #fff; }
.header { background: #1a3c6e; color: #fff; padding: 24px 30px; }
.header h1 { font-size: 24px; letter-spacing: 2px; margin-bottom: 4px; }
.header p { font-size: 11px; opacity: 0.8; }
.badge { display: inline-block; background: #28a745; color: #fff; padding: 3px 12px; border-radius: 12px; font-size: 10px; font-weight: bold; margin-top: 8px; }
.body { padding: 24px 30px; }
.meta-table { width: 100%; margin-bottom: 20px; }
.meta-table td { vertical-align: top; width: 50%; padding: 0; }
.meta-table .right { text-align: right; }
.label { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #1a3c6e; letter-spacing: 0.8px; margin-bottom: 5px; }
.meta-val { font-size: 12px; margin-bottom: 2px; }
.divider { border: none; border-top: 1px solid #e0e0e0; margin: 16px 0; }
.section-title { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #1a3c6e; letter-spacing: 0.8px; margin: 16px 0 8px 0; }
table.tbl { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
table.tbl th { background: #f0f4fb; color: #555; font-size: 10px; text-transform: uppercase; padding: 7px 10px; text-align: left; border: 1px solid #dde3f0; width: 35%; }
table.tbl td { padding: 7px 10px; border: 1px solid #dde3f0; font-size: 11px; }
table.tbl tr:nth-child(even) td { background: #fafbff; }
.amount-box { background: #f0f4fb; border: 1px solid #c8d6f0; border-radius: 4px; padding: 12px 16px; margin-top: 16px; text-align: right; }
.amount-box .amount-label { font-size: 11px; color: #666; }
.amount-box .amount-val { font-size: 20px; font-weight: bold; color: #1a3c6e; }
.footer { margin-top: 24px; text-align: center; font-size: 10px; color: #aaa; border-top: 1px solid #eee; padding-top: 14px; }
.footer strong { color: #1a3c6e; }
</style>
</head>
<body>

<div class="header">
    <h1>INVOICE</h1>
    <p>Swap Circle &mdash; Official Purchase Receipt</p>
    <span class="badge">&#10004; PAID</span>
</div>

<div class="body">

    <table class="meta-table">
        <tr>
            <td>
                <div class="label">Billed To</div>
                @if($customer)
                <div class="meta-val"><strong>{{ $customer->first_name }} {{ $customer->last_name ?? '' }}</strong></div>
                <div class="meta-val">{{ $customer->email }}</div>
                <div class="meta-val">{{ $customer->phone }}</div>
                @endif
            </td>
            <td class="right">
                <div class="label">Invoice Details</div>
                <div class="meta-val"><strong>Invoice #:</strong> INV-{{ $purchase->products_purchases_id }}</div>
                <div class="meta-val"><strong>Transaction ID:</strong> {{ $purchase->products_purchases_id }}</div>
                <div class="meta-val"><strong>Date Issued:</strong> {{ date('d M Y') }}</div>
                <div class="meta-val"><strong>Status:</strong> {{ $purchase->payment_status }}</div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    <div class="section-title">Product Details</div>
    <table class="tbl">
        <tr><th>Product Name</th><td>{{ $product->name ?? '-' }}</td></tr>
        <tr><th>Product Type</th><td>{{ $purchase->product_type }}</td></tr>
        <tr><th>Cover Duration</th><td>{{ $purchase->cover_duration }}</td></tr>
        <tr><th>Cover Start Date</th><td>{{ date('d M Y', strtotime($purchase->cover_start_date)) }}</td></tr>
        <tr><th>Cover End Date</th><td>{{ date('d M Y', strtotime($purchase->cover_end_date)) }}</td></tr>
    </table>

    @if($beneficiary)
    <div class="section-title">Beneficiary Details</div>
    <table class="tbl">
        <tr><th>First Name</th><td>{{ $beneficiary->first_name }}</td></tr>
        <tr><th>Surname</th><td>{{ $beneficiary->surname ?? '-' }}</td></tr>
        <tr><th>Gender</th><td>{{ $beneficiary->gender }}</td></tr>
        <tr><th>Date of Birth</th><td>{{ date('d M Y', strtotime($beneficiary->date_of_birth)) }}</td></tr>
        <tr><th>Address</th><td>{{ $beneficiary->address }}</td></tr>
        <tr><th>Occupation</th><td>{{ $beneficiary->occupation }}</td></tr>
        <tr><th>Relationship</th><td>{{ $beneficiary->relationship }}</td></tr>
        <tr><th>Phone Number</th><td>{{ $beneficiary->phone_number ?? 'Not Provided' }}</td></tr>
    </table>
    @endif

    @if($task)
    <div class="section-title">Task Details</div>
    <table class="tbl">
        <tr><th>Task</th><td>{{ $task->task }}</td></tr>
        <tr><th>Task Date</th><td>{{ date('d M Y', strtotime($task->task_date)) }}</td></tr>
        <tr><th>Description</th><td>{{ $task->description }}</td></tr>
        <tr><th>Contact Person</th><td>{{ $task->recipient_name }}</td></tr>
        <tr><th>Contact Phone</th><td>{{ $task->recipient_phone }}</td></tr>
        <tr><th>Deliveries Limit</th><td>{{ $task->delivery_request_limit }}</td></tr>
        <tr><th>Deliveries Used</th><td>{{ $task->delivery_requests_consumed }}</td></tr>
    </table>
    @endif

    <div class="amount-box">
        <div class="amount-label">Total Amount Paid</div>
        <div class="amount-val">&pound;{{ number_format($product->price ?? 0, 2) }}</div>
    </div>

    <div class="footer">
        Thank you for choosing <strong>Swap Circle</strong>. This is an official invoice.<br>
        For support: <strong>support@swapcircle.trade</strong>
    </div>

</div>
</body>
</html>
