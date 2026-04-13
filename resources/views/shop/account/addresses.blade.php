@extends('layouts.account')
@section('title', 'Kargo Adreslerim')

@section('account_content')
<div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 12px; margin-bottom: 20px;">
    <h2 style="font-size: 20px; margin: 0;">Kargo Adreslerim</h2>
</div>

<div class="card" style="padding: 24px; margin-bottom: 24px; background: white; border: 1px solid #eee; border-radius: 8px;">
    <h3 style="font-size: 16px; margin-bottom: 16px;">Yeni Adres Ekle</h3>
    <form action="{{ route('account.addresses.store') }}" method="POST">
        @csrf
        <div style="display: flex; gap: 16px; margin-bottom: 16px;">
            <div style="flex: 1;">
                <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Adres Başlığı (Örn: Ev, İş)</label>
                <input type="text" name="title" required style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
            </div>
        </div>
        <div style="display: flex; gap: 16px; margin-bottom: 16px;">
            <div style="flex: 1;">
                <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Ad</label>
                <input type="text" name="first_name" required style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <div style="flex: 1;">
                <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Soyad</label>
                <input type="text" name="last_name" required style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
            </div>
        </div>
        <div style="display: flex; gap: 16px; margin-bottom: 16px;">
            <div style="flex: 1;">
                <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Telefon</label>
                <input type="text" name="phone" required style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <div style="flex: 1;">
                <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">İl</label>
                <input type="text" name="city" required style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <div style="flex: 1;">
                <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">İlçe</label>
                <input type="text" name="district" required style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
            </div>
        </div>
        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Açık Adres</label>
            <textarea name="address" rows="3" required style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px; resize:vertical;"></textarea>
        </div>
        <button type="submit" style="background:var(--primary); color:white; padding:10px 20px; border:none; border-radius:6px; font-weight:bold; cursor:pointer;">
            Adres Ekle
        </button>
    </form>
</div>

<h3 style="font-size: 18px; margin-bottom: 16px;">Kayıtlı Adresleriniz</h3>
<div style="display: flex; flex-wrap: wrap; gap: 20px;">
    @foreach($addresses as $addr)
    <div style="width: calc(50% - 10px); background: white; border: 1px solid #eee; border-radius: 8px; padding: 20px; position:relative;">
        <h4 style="margin: 0 0 10px; font-size: 15px; color: var(--primary);">{{ $addr->title }}</h4>
        <p style="margin: 0 0 4px; font-size: 14px; font-weight:bold;">{{ $addr->first_name }} {{ $addr->last_name }}</p>
        <p style="margin: 0 0 4px; font-size: 13px; color: #666;">{{ $addr->phone }}</p>
        <p style="margin: 0 0 4px; font-size: 13px; color: #666;">{{ $addr->city }}, {{ $addr->district }}</p>
        <p style="margin: 0 0 16px; font-size: 13px; color: #666;">{{ $addr->address }}</p>
        
        <form action="{{ route('account.addresses.delete', $addr->id) }}" method="POST" style="position: absolute; right: 20px; top: 20px;">
            @csrf @method('DELETE')
            <button type="submit" onclick="return confirm('Bu adresi silmek istediğinize emin misiniz?')" style="background: transparent; border: none; color: var(--danger); cursor: pointer;"><i class="fas fa-trash"></i></button>
        </form>
    </div>
    @endforeach
</div>
@endsection
