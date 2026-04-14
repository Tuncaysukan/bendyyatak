@extends('layouts.admin')

@section('title', 'PayTR Ödemeleri')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">PayTR Ödemeleri</h1>
        <p class="page-subtitle">Tüm PayTR ödeme islemlerini takip edin</p>
    </div>
    <div style="display:flex; gap:8px;">
        <a href="{{ route('admin.paytr.export') }}" class="btn btn-primary">
            <i class="fas fa-download"></i> Export
        </a>
    </div>
</div>

<!-- Istatistik Kartlari -->
<div class="grid grid-4" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-credit-card"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Toplam Islem</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-value">{{ $stats['success'] }}</div>
            <div class="stat-label">Basarili Ödeme</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-times-circle"></i></div>
        <div>
            <div class="stat-value">{{ $stats['failed'] }}</div>
            <div class="stat-label">Basarisiz</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-chart-line"></i></div>
        <div>
            <div class="stat-value">₺{{ number_format($stats['total_amount'], 2, ',', '.') }}</div>
            <div class="stat-label">Toplam Ciro</div>
        </div>
    </div>
</div>

<!-- Filtreler -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter" style="color:var(--primary);margin-right:8px;"></i> Filtrele</h3>
        <a href="{{ route('admin.paytr.index') }}" class="btn btn-sm btn-outline">Temizle</a>
    </div>
    <form method="GET" action="{{ route('admin.paytr.index') }}">
        <div class="grid grid-4" style="gap:16px;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Durum</label>
                <select name="status" class="form-control">
                    <option value="">Tümü</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Beklemede</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Basarili</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Basarisiz</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Iptal</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Iade</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Baslangic</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Bitis</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Arama</label>
                <input type="text" name="search" class="form-control" placeholder="Siparis no, e-posta..." value="{{ request('search') }}">
            </div>
        </div>
        <div style="margin-top:16px; text-align:right;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrele</button>
        </div>
    </form>
</div>

<!-- Islemler Tablosu -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-receipt" style="color:var(--primary);margin-right:8px;"></i> Ödeme Islemleri</h3>
        <span style="font-size:12px;color:var(--text-muted);">{{ $transactions->total() }} kayit</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Siparis No</th>
                    <th>Müsteri</th>
                    <th>Tutar</th>
                    <th>Taksit</th>
                    <th>Banka / Kart</th>
                    <th>Durum</th>
                    <th>Islem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td style="white-space:nowrap;">{{ $transaction->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($transaction->order)
                            <a href="{{ route('admin.orders.show', $transaction->order) }}" style="color:var(--primary);font-weight:600;">
                                {{ $transaction->order->order_no }}
                            </a>
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($transaction->order)
                            <div style="font-weight:500;">{{ $transaction->order->customer_name ?? '-' }}</div>
                            <div style="font-size:11.5px;color:var(--text-muted);">{{ $transaction->order->customer_email }}</div>
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td><strong>₺{{ number_format($transaction->amount, 2, ',', '.') }}</strong></td>
                    <td>
                        @if($transaction->installment_count > 1)
                            <span class="badge badge-primary">{{ $transaction->installment_count }} taksit</span>
                        @else
                            <span style="color:var(--text-muted);">Tek Çekim</span>
                        @endif
                    </td>
                    <td>
                        @if($transaction->card_bank)
                            <div style="font-weight:500;">{{ $transaction->card_bank }}</div>
                            <div style="font-size:11.5px;color:var(--text-muted);">{{ $transaction->card_brand }} ****{{ $transaction->card_last_four }}</div>
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusBadge = match($transaction->status) {
                                'success' => 'badge-success',
                                'pending' => 'badge-warning',
                                'failed' => 'badge-danger',
                                'cancelled' => 'badge-secondary',
                                'refunded' => 'badge-info',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ $transaction->status_label }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.paytr.show', $transaction) }}" class="btn btn-sm btn-outline" title="Detay">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($transaction->order)
                                <a href="{{ route('admin.orders.show', $transaction->order) }}" class="btn btn-sm btn-outline" title="Siparis">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:48px 20px;">
                        <div style="font-size:40px;color:var(--text-light);margin-bottom:12px;"><i class="fas fa-inbox"></i></div>
                        <div style="font-size:15px;font-weight:600;color:var(--text-muted);margin-bottom:4px;">Henuz ödeme islemi yok</div>
                        <div style="font-size:13px;color:var(--text-light);">PayTR ile yapilan ödemeler burada listelenecek</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($transactions->hasPages())
    <div style="padding:16px 0 4px;display:flex;justify-content:center;">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
