<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket {{ $order->code }} · EventTicket</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            padding: 24px;
        }
        .toolbar {
            max-width: 640px;
            margin: 0 auto 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
        }
        .toolbar a, .toolbar button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }
        .btn-print {
            background: #4f46e5;
            color: #fff;
        }
        .btn-print:hover { background: #4338ca; }
        .btn-back {
            background: #fff;
            color: #475569;
            border: 1px solid #e2e8f0 !important;
        }
        .hint {
            font-size: 12px;
            color: #64748b;
            max-width: 640px;
            margin: 0 auto 16px;
        }
        .ticket {
            max-width: 640px;
            margin: 0 auto 24px;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(79, 70, 229, 0.12);
            border: 1px solid #e2e8f0;
            page-break-inside: avoid;
            break-inside: avoid;
            /* Paksa background ikut tercetak di PDF */
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        .ticket-body {
            display: flex;
            min-height: 180px;
        }
        .ticket-main {
            flex: 1;
            padding: 24px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #fff !important;
            min-width: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        .ticket-main .type {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            opacity: 0.9;
            margin-bottom: 6px;
            color: #fff !important;
        }
        .ticket-main h1 {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 14px;
            color: #fff !important;
        }
        .ticket-main .meta {
            font-size: 13px;
            opacity: 0.95;
            line-height: 1.6;
            color: #fff !important;
        }
        .ticket-main .meta strong {
            display: block;
            font-weight: 600;
            opacity: 1;
            color: #fff !important;
        }
        .ticket-main .meta .sub {
            color: #e0e7ff !important;
            opacity: 0.95;
        }
        .ticket-stub {
            width: 160px;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-left: 2px dashed #e2e8f0;
            position: relative;
            background: #fff;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .ticket-stub::before,
        .ticket-stub::after {
            content: '';
            position: absolute;
            left: -9px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #f1f5f9;
        }
        .ticket-stub::before { top: -8px; }
        .ticket-stub::after { bottom: -8px; }
        .stub-label {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }
        .stub-code {
            font-family: ui-monospace, 'Cascadia Code', monospace;
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.08em;
            word-break: break-all;
            line-height: 1.3;
        }
        .stub-status {
            margin-top: 12px;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .stub-status.valid {
            background: #ecfdf5 !important;
            color: #047857 !important;
        }
        .stub-status.used {
            background: #f1f5f9 !important;
            color: #64748b !important;
        }
        .ticket-footer {
            padding: 12px 24px;
            background: #f8fafc !important;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #64748b;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .ticket-footer .brand {
            font-weight: 600;
            color: #4f46e5;
        }
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
            }
            .toolbar, .hint { display: none !important; }
            .ticket {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                margin-bottom: 16px !important;
                border-radius: 12px !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            .ticket-main {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
                color: #fff !important;
            }
            .ticket-main * {
                color: #fff !important;
            }
            .ticket-stub::before,
            .ticket-stub::after {
                background: #fff !important;
            }
            .stub-status.valid {
                background: #ecfdf5 !important;
                color: #047857 !important;
            }
            .ticket-footer {
                background: #f8fafc !important;
            }
        }
        @media (max-width: 520px) {
            .ticket-body { flex-direction: column; }
            .ticket-stub {
                width: 100%;
                border-left: none;
                border-top: 2px dashed #e2e8f0;
                flex-direction: row;
                gap: 16px;
                justify-content: space-between;
                padding: 16px 24px;
            }
            .ticket-stub::before,
            .ticket-stub::after { display: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('orders.show', $order) }}" class="btn-back">← Kembali ke order</a>
        <button type="button" class="btn-print" onclick="window.print()">
            ⬇ Unduh / Cetak PDF
        </button>
    </div>
    <p class="hint">
        Klik <strong>Unduh / Cetak PDF</strong>, lalu pilih printer <em>Save as PDF</em> / <em>Microsoft Print to PDF</em>.
        Pastikan opsi <strong>Background graphics</strong> / <strong>Grafik latar belakang</strong> aktif agar warna tiket ikut tercetak.
    </p>

    @foreach ($tickets as $ticket)
        @php
            $item = $ticket->orderItem;
            $ticketType = $item?->ticketType;
            $event = $ticketType?->event;
            $typeName = $ticketType?->name ?? 'Tiket Masuk';
            $eventTitle = $event?->title ?? 'Event';

            // Ambil style latar belakang kustom admin (fallback ke gradasi default)
            $ticketBgStyle = ($event && !empty($event->ticket_style))
                ? $event->ticket_style
                : 'background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);';
        @endphp
        <article class="ticket">
            <div class="ticket-body">
                {{-- Bagian Utama Tiket (sama seperti preview di halaman order) --}}
                <div class="ticket-main" style="{{ $ticketBgStyle }}">
                    <p class="type">{{ $typeName }}</p>
                    <h1>{{ $eventTitle }}</h1>
                    <div class="meta">
                        @if ($event && $event->starts_at)
                            <strong>{{ $event->starts_at->translatedFormat('l, d F Y · H:i') }} WIB</strong>
                            <div class="sub">{{ $event->venue }}@if($event->city), {{ $event->city }}@endif</div>
                        @endif
                        <div style="margin-top: 10px; font-size: 12px; opacity: 0.95;">
                            Nama Pemesan: <strong>{{ $order->user?->name ?? 'Pengunjung' }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Sobekan Tiket (Stub) & Kode Validasi --}}
                <div class="ticket-stub">
                    <div>
                        <p class="stub-label">Kode tiket</p>
                        <p class="stub-code">{{ $ticket->code }}</p>
                    </div>
                    <span class="stub-status {{ $ticket->is_used ? 'used' : 'valid' }}">
                        {{ $ticket->is_used ? 'Sudah dipakai' : 'Valid' }}
                    </span>
                </div>
            </div>

            <div class="ticket-footer">
                <span class="brand">EventTicket</span>
                <span>Order {{ $order->code }} · {{ $order->user?->name ?? '' }}</span>
            </div>
        </article>
    @endforeach

    <script>
        // Opsional: auto-buka dialog print jika ?print=1
        if (new URLSearchParams(location.search).get('print') === '1') {
            window.addEventListener('load', () => setTimeout(() => window.print(), 300));
        }
    </script>
</body>
</html>
