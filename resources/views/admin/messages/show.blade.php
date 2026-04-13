@extends('layouts.admin')
@section('title', 'Mesaj Oku')
@section('topbar-title', 'Gelen Kutusu')

@section('content')
<div class="page-header">
    <div class="page-title">Mesaj Oku</div>
    <div class="page-actions">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri Dön</a>
    </div>
</div>

<div class="card" style="max-width:800px;">
    <div style="border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 20px;">
        <h3 style="font-size: 20px; margin-bottom: 12px; color: var(--text);">{{ $message->subject }}</h3>
        <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 13.5px;">
            <div>
                <strong>Gönderen:</strong> {{ $message->name }} &lt;<a href="mailto:{{ $message->email }}" style="color:var(--primary);text-decoration:none;">{{ $message->email }}</a>&gt;
            </div>
            <div>
                <i class="far fa-calendar-alt"></i> {{ $message->created_at->format('d.m.Y H:i') }}
            </div>
        </div>
    </div>
    
    <div style="font-size: 14.5px; line-height: 1.6; color: var(--text); background: #f9fafb; padding: 24px; border-radius: 8px;">
        {!! nl2br(e($message->message)) !!}
    </div>
    
    <div style="margin-top:24px; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
        <a href="mailto:{{ $message->email }}?subject=RE: {{ $message->subject }}" class="btn btn-primary">
            <i class="fas fa-reply"></i> Yanıtla (E-Posta)
        </a>
        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST">
            @csrf @method('DELETE')
            <button type="button" class="btn btn-danger" onclick="confirmDelete(this)"><i class="fas fa-trash"></i> Sil</button>
        </form>
    </div>
</div>
@endsection
