<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Voucher {{ $booking->reference }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #1e293b; font-size: 12px; margin: 0; padding: 0; }
        .wrap { padding: 32px 36px; }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 16px; margin-bottom: 24px; }
        .brand { font-size: 22px; font-weight: bold; color: #1d4ed8; }
        .muted { color: #64748b; }
        .title { font-size: 16px; font-weight: bold; margin: 0 0 4px; }
        .doc-meta { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        .two-col td { vertical-align: top; width: 50%; padding: 0; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-confirmed { background: #dcfce7; color: #15803d; }
        .badge-pending { background: #fef9c3; color: #a16207; }
        .badge-completed { background: #dbeafe; color: #1d4ed8; }
        .badge-cancelled { background: #fee2e2; color: #b91c1c; }
        .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; color: #2563eb; margin: 24px 0 8px; }
        .data td { padding: 6px 0; border-bottom: 1px solid #e2e8f0; }
        .data td.label { color: #64748b; width: 38%; }
        .totals { margin-top: 12px; width: 280px; float: right; }
        .totals td { padding: 5px 0; }
        .totals .grand { border-top: 2px solid #1e293b; font-size: 14px; font-weight: bold; }
        .footer { clear: both; margin-top: 60px; border-top: 1px solid #e2e8f0; padding-top: 14px; font-size: 10px; color: #94a3b8; text-align: center; }
        .note { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; margin-top: 8px; }
    </style>
</head>
<body>
@php
    $symbol = setting('currency_symbol', '$');
    $siteName = setting('site_name', config('app.name'));
@endphp
<div class="wrap">
    <table class="header">
        <tr>
            <td>
                <div class="brand">{{ $siteName }}</div>
                <div class="muted">{{ setting('email') }} @if(setting('phone')) · {{ setting('phone') }} @endif</div>
                @if(setting('address'))<div class="muted">{{ setting('address') }}</div>@endif
            </td>
            <td class="doc-meta">
                <div class="title">BOOKING VOUCHER</div>
                <div class="muted">Ref: <strong>{{ $booking->reference }}</strong></div>
                <div class="muted">Issued: {{ $booking->created_at->format('M d, Y') }}</div>
                <div style="margin-top:6px;">
                    <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="two-col">
        <tr>
            <td>
                <div class="section-title">Traveller</div>
                <div><strong>{{ $booking->name }}</strong></div>
                <div class="muted">{{ $booking->email }}</div>
                <div class="muted">{{ $booking->phone }}</div>
            </td>
            <td>
                <div class="section-title">Trip</div>
                <div><strong>{{ optional($booking->package)->title ?? 'Custom inquiry' }}</strong></div>
                @if($booking->destination)<div class="muted">{{ $booking->destination->name }}</div>@endif
                @if($booking->package)<div class="muted">{{ $booking->package->duration_label }}</div>@endif
            </td>
        </tr>
    </table>

    <div class="section-title">Booking Details</div>
    <table class="data">
        <tr><td class="label">Travel Date</td><td>{{ $booking->travel_date ? $booking->travel_date->format('M d, Y') : 'To be confirmed' }}</td></tr>
        <tr><td class="label">Travellers</td><td>{{ $booking->adults }} adult(s), {{ $booking->children }} child(ren)</td></tr>
        @if($booking->message)
        <tr><td class="label">Special Requests</td><td>{{ $booking->message }}</td></tr>
        @endif
        @if($booking->admin_note)
        <tr><td class="label">Agent Note</td><td>{{ $booking->admin_note }}</td></tr>
        @endif
    </table>

    @if($booking->subtotal !== null && (float) $booking->subtotal > 0)
        <table class="totals">
            <tr><td>Subtotal</td><td style="text-align:right;">{{ $symbol }}{{ number_format($booking->subtotal, 2) }}</td></tr>
            @if((float) $booking->discount_amount > 0)
            <tr>
                <td>Discount @if($booking->coupon_code)({{ $booking->coupon_code }})@endif</td>
                <td style="text-align:right; color:#15803d;">- {{ $symbol }}{{ number_format($booking->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="grand"><td>Total</td><td style="text-align:right;">{{ $symbol }}{{ number_format($booking->total, 2) }}</td></tr>
        </table>
        <div style="clear:both;"></div>
        <div class="note muted">Prices are an estimate based on your selection and may be adjusted by our team upon confirmation.</div>
    @endif

    <div class="footer">
        Thank you for choosing {{ $siteName }}. Please present this voucher reference when contacting our team.<br>
        This document was generated on {{ $booking->updated_at->format('M d, Y') }} and is not a tax invoice unless marked as paid.
    </div>
</div>
</body>
</html>
