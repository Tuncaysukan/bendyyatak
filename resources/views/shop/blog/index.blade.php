@extends('layouts.app')

@section('seo_title', 'Blog — BendyyYatak')
@section('seo_description', 'Yatak seçimi rehberleri, uyku sağlığı ve dekorasyon ipuçları.')

@section('content')
<div class="container" style="padding-top:32px;padding-bottom:60px;">
    <div style="text-align:center;max-width:560px;margin:0 auto 40px;">
        <div style="font-size:12px;color:#a8893e;font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:10px;">Blog</div>
        <h1 style="font-size:30px;font-weight:800;color:#1a1a2e;">Yatak ve Uyku Rehberleri</h1>
        <p style="color:#6b7280;margin-top:10px;line-height:1.7;">Doğru yatak seçimi, uyku sağlığı ve ev dekorasyonu hakkında uzman ipuçları.</p>
    </div>

    @if($posts->isEmpty())
    <div style="text-align:center;padding:60px;color:#9ca3af;">
        <i class="fas fa-pen-nib" style="font-size:48px;display:block;margin-bottom:16px;color:#e5e7eb;"></i>
        <p>Henüz blog yazısı yok.</p>
    </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
        @foreach($posts as $post)
        <a href="{{ route('blog.show', $post) }}" style="text-decoration:none;display:block;">
            <article style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;transition:transform .25s,box-shadow .25s;"
                     onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,.08)'"
                     onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div style="aspect-ratio:16/9;background:#f5f5f0;overflow:hidden;">
                    @if($post->cover_image)
                        <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}"
                             style="width:100%;height:100%;object-fit:cover;transition:transform .4s;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#e5e7eb;font-size:40px;">
                            <i class="fas fa-pen-nib"></i>
                        </div>
                    @endif
                </div>
                <div style="padding:20px;">
                    @if($post->category)
                        <span style="font-size:11px;color:#a8893e;font-weight:700;text-transform:uppercase;letter-spacing:.06em;">{{ $post->category->name }}</span>
                    @endif
                    <h2 style="font-size:16px;font-weight:700;color:#1a1a2e;margin:8px 0;line-height:1.4;">{{ Str::limit($post->title, 65) }}</h2>
                    @if($post->excerpt)
                        <p style="font-size:13px;color:#6b7280;line-height:1.6;">{{ Str::limit($post->excerpt, 110) }}</p>
                    @endif
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:14px;">
                        <span style="font-size:12px;color:#9ca3af;"><i class="fas fa-calendar fa-xs"></i> {{ $post->published_at?->format('d M Y') }}</span>
                        <span style="font-size:12px;color:#a8893e;font-weight:600;">Devamını oku →</span>
                    </div>
                </div>
            </article>
        </a>
        @endforeach
    </div>
    <div style="margin-top:40px;">{{ $posts->links() }}</div>
    @endif
</div>
@endsection

@push('styles')
<style>
@media(max-width:768px) {
    [style*="grid-template-columns:repeat(3,1fr)"] { grid-template-columns: repeat(2, 1fr) !important; }
}
@media(max-width:480px) {
    [style*="grid-template-columns:repeat(3,1fr)"] { grid-template-columns: 1fr !important; }
}
</style>
@endpush
