<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Pembayaran - {{ $order->invoice_number }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: Arial, sans-serif; color: #0f172a;">
    @php
        $brandName = config('brand.name', 'Igrenc');
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; background-color: #ffffff; border-radius: 24px; overflow: hidden; border: 1px solid #e2e8f0;">
                    <tr>
                        <td style="padding: 32px; background-color: #111827; color: #ffffff;">
                            <div style="font-size: 24px; font-weight: 800; letter-spacing: -0.02em;">
                                {{ $brandName }}
                            </div>

                            <div style="margin-top: 6px; font-size: 13px; font-weight: 700; color: #c7d2fe; text-transform: uppercase; letter-spacing: 0.08em;">
                                Invoice Pembayaran
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 32px;">
                            <h1 style="margin: 0; font-size: 22px; line-height: 1.4; color: #0f172a;">
                                Pembayaran Berhasil
                            </h1>

                            <p style="margin: 16px 0 0; font-size: 15px; line-height: 1.7; color: #475569;">
                                Halo {{ $order->customer_name ?: 'Pelanggan' }},
                            </p>

                            <p style="margin: 8px 0 0; font-size: 15px; line-height: 1.7; color: #475569;">
                                Pembayaran untuk pesanan Anda telah berhasil diterima. Detail invoice dapat dilihat pada informasi berikut.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 24px; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 12px 0; font-size: 14px; color: #64748b;">Invoice</td>
                                    <td align="right" style="padding: 12px 0; font-size: 14px; font-weight: 700; color: #0f172a;">
                                        {{ $order->invoice_number }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 12px 0; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">Status</td>
                                    <td align="right" style="padding: 12px 0; font-size: 14px; font-weight: 700; color: #059669; border-top: 1px solid #e2e8f0;">
                                        Sudah Dibayar
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 12px 0; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">Tanggal Pembayaran</td>
                                    <td align="right" style="padding: 12px 0; font-size: 14px; font-weight: 700; color: #0f172a; border-top: 1px solid #e2e8f0;">
                                        {{ $order->paid_at?->format('d M Y H:i') ?: '-' }}
                                    </td>
                                </tr>
                            </table>

                            <h2 style="margin: 28px 0 12px; font-size: 16px; color: #0f172a;">
                                Detail Pesanan
                            </h2>

                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 14px 16px; background-color: #f8fafc; font-size: 13px; font-weight: 700; color: #475569;">Keterangan</td>
                                    <td align="right" style="padding: 14px 16px; background-color: #f8fafc; font-size: 13px; font-weight: 700; color: #475569;">Detail</td>
                                </tr>

                                <tr>
                                    <td style="padding: 14px 16px; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">Game</td>
                                    <td align="right" style="padding: 14px 16px; font-size: 14px; font-weight: 700; color: #0f172a; border-top: 1px solid #e2e8f0;">{{ $order->game_name }}</td>
                                </tr>

                                <tr>
                                    <td style="padding: 14px 16px; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">Produk</td>
                                    <td align="right" style="padding: 14px 16px; font-size: 14px; font-weight: 700; color: #0f172a; border-top: 1px solid #e2e8f0;">{{ $order->product_name }}</td>
                                </tr>

                                <tr>
                                    <td style="padding: 14px 16px; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">Metode Pembayaran</td>
                                    <td align="right" style="padding: 14px 16px; font-size: 14px; font-weight: 700; color: #0f172a; border-top: 1px solid #e2e8f0;">
                                        {{ $order->paymentGateway?->display_label ?: $order->paymentGateway?->name ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 14px 16px; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">Harga Produk</td>
                                    <td align="right" style="padding: 14px 16px; font-size: 14px; font-weight: 700; color: #0f172a; border-top: 1px solid #e2e8f0;">
                                        Rp {{ number_format((int) $order->product_price, 0, ',', '.') }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 14px 16px; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">Biaya Admin</td>
                                    <td align="right" style="padding: 14px 16px; font-size: 14px; font-weight: 700; color: #0f172a; border-top: 1px solid #e2e8f0;">
                                        Rp {{ number_format((int) $order->admin_fee, 0, ',', '.') }}
                                    </td>
                                </tr>

                                @if ((int) ($order->discount_amount ?? 0) > 0)
                                    <tr>
                                        <td style="padding: 14px 16px; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">Voucher</td>
                                        <td align="right" style="padding: 14px 16px; font-size: 14px; font-weight: 700; color: #059669; border-top: 1px solid #e2e8f0;">
                                            {{ $order->voucher_code ?: '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="padding: 14px 16px; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">Potongan Harga</td>
                                        <td align="right" style="padding: 14px 16px; font-size: 14px; font-weight: 700; color: #059669; border-top: 1px solid #e2e8f0;">
                                            - Rp {{ number_format((int) $order->discount_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td style="padding: 16px; font-size: 15px; font-weight: 800; color: #0f172a; border-top: 1px solid #e2e8f0;">Total Pembayaran</td>
                                    <td align="right" style="padding: 16px; font-size: 16px; font-weight: 900; color: #0f172a; border-top: 1px solid #e2e8f0;">
                                        Rp {{ number_format((int) $order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>

                            @if (! empty($order->customer_inputs))
                                <h2 style="margin: 28px 0 12px; font-size: 16px; color: #0f172a;">
                                    Data Akun
                                </h2>

                                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden;">
                                    <tr>
                                        <td style="padding: 14px 16px; background-color: #f8fafc; font-size: 13px; font-weight: 700; color: #475569;">Kolom</td>
                                        <td align="right" style="padding: 14px 16px; background-color: #f8fafc; font-size: 13px; font-weight: 700; color: #475569;">Nilai</td>
                                    </tr>

                                    @foreach ($order->customer_inputs as $label => $value)
                                        <tr>
                                            <td style="padding: 14px 16px; font-size: 14px; color: #64748b; border-top: 1px solid #e2e8f0;">
                                                {{ str_replace(['User Id', 'User id'], 'User ID', $label) }}
                                            </td>
                                            <td align="right" style="padding: 14px 16px; font-size: 14px; font-weight: 700; color: #0f172a; border-top: 1px solid #e2e8f0;">
                                                {{ $value ?: '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            <div style="margin-top: 32px; text-align: center;">
                                <a
                                    href="{{ route('orders.show', $order->invoice_number) }}"
                                    style="display: inline-block; background-color: #4f46e5; color: #ffffff; text-decoration: none; padding: 14px 22px; border-radius: 16px; font-size: 14px; font-weight: 800;"
                                >
                                    Lihat Detail Invoice
                                </a>
                            </div>

                            <p style="margin: 28px 0 0; font-size: 14px; line-height: 1.7; color: #64748b;">
                                Terima kasih telah menggunakan {{ $brandName }}. Pesanan Anda akan diproses sesuai status pembayaran yang telah diterima.
                            </p>
                        </td>
                    </tr>
                </table>

                <p style="margin: 18px 0 0; font-size: 12px; color: #94a3b8;">
                    © {{ now()->year }} {{ $brandName }}. Semua hak dilindungi.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
