@extends('layouts.app')

@section('seo_title', ($post->seo_title ?? $post->title) . ' — BendyyYatak')
@section('seo_description', $post->seo_description ?? $post->excerpt)
@section('og_image', $post->cover_image ? asset('storage/'.$post->cover_image) : '')

@section('content')
<div class="container" style="padding-top:32px;padding-bottom:60px;">
    <div style="max-width:760px;margin:0 auto;">
        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Anasayfa</a>
            <span>/</span>
            <a href="{{ route('blog.index') }}">Blog</a>
            <span>/</span>
            <span>{{ Str::limit($post->title, 40) }}</span>
        </div>

        {{-- Başlık --}}
        @if($post->category)
            <span style="font-size:12px;color:#a8893e;font-weight:700;text-transform:uppercase;letter-spacing:.06em;">{{ $post->category->name }}</span>
        @endif
        <h1 style="font-size:30px;font-weight:800;color:#1a1a2e;line-height:1.25;margin:12px 0 16px;">{{ $post->title }}</h1>
        <div style="display:flex;align-items:center;gap:16px;color:#9ca3af;font-size:13px;margin-bottom:28px;">
            <span><i class="fas fa-calendar fa-xs"></i> {{ $post->published_at?->format('d F Y') }}</span>
        </div>

        {{-- Kapak Görseli --}}
        @if($post->cover_image)
        <div style="border-radius:16px;overflow:hidden;margin-bottom:32px;aspect-ratio:16/9;">
            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
        </div>
        @endif

        {{-- İçerik --}}
        <div style="font-size:15.5px;color:#374151;line-height:1.85;">
            {!! nl2br(e($post->content)) !!}
        </div>

        {{-- İlgili Yazılar --}}
        @if($related->isNotEmpty())
        <div style="margin-top:48px;padding-top:32px;border-top:1px solid #e5e7eb;">
            <h2 style="font-size:18px;font-weight:700;margin-bottom:20px;">İlgili Yazılar</h2>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                @foreach($related as $relPost)
                <a href="{{ route('blog.show', $relPost) }}" style="text-decoration:none;">
                    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
                        @if($relPost->cover_image)
                            <img src="{{ asset('storage/'.$relPost->cover_image) }}" alt="{{ $relPost->title }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;">
                        @endif
                        <div style="padding:12px;">
                            <div style="font-size:13.5px;font-weight:600;color:#1a1a2e;line-height:1.4;">{{ Str::limit($relPost->title, 50) }}</div>
                            <div style="font-size:11.5px;color:#9ca3af;margin-top:6px;">{{ $relPost->published_at?->format('d M Y') }}</div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div style="text-align:center;margin-top:36px;">
            <a href="{{ route('blog.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Tüm Yazılar</a>
        </div>
    </div>
</div>
@endsection
