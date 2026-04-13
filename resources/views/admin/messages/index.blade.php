@extends('layouts.admin')
@section('title', 'Gelen Kutusu')
@section('topbar-title', 'Gelen Kutusu')

@section('content')
<div class="page-header">
    <div class="page-title">İletişim Mesajları</div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Durum</th>
                    <th>Gönderen</th>
                    <th>Konu</th>
                    <th>Tarih</th>
                    <th style="width:100px;">İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr style="{{ !$msg->is_read ? 'background:#f9fafe; font-weight:600;' : '' }}">
                    <td>
                        @if(!$msg->is_read)
                            <span class="badge badge-primary">Yeni</span>
                        @else
                            <span class="badge badge-secondary">Okundu</span>
                        @endif
                    </td>
                    <td>
                        <div>{{ $msg->name }}</div>
                        <div style="font-size:12px; color:var(--text-muted); font-weight:normal;">{{ $msg->email }}</div>
                    </td>
                    <td>{{ \Str::limit($msg->subject, 50) }}</td>
                    <td style="color:var(--text-muted);">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> İncele</a>
                            <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(this)" data-name="Mesaj"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:40px; color:var(--text-muted);">Henüz hiç mesajınız yok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top:20px;">
        {{ $messages->links() }}
    </div>
</div>
@endsection
