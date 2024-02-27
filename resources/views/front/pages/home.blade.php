@extends('front.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Beranda Masjid Nurul Qurba')
@section('meta_tags')
    <meta name="robots" content="index,follow" />
    <meta name="title" content="{{ blogInfo()->blog_name }}" />
    <meta name="description" content="{{ blogInfo()->blog_description }}" />
    <meta name="author" content="{{ blogInfo()->blog_name }}" />
    <link rel="canonical" href="{{ Request::root() }}">
    <meta property="og:title" content="{{ blogInfo()->blog_name }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ blogInfo()->blog_description }}" />
    <meta property="og:url" content="{{ Request::root() }}">
    <meta property="og:image" content="{{ blogInfo()->blog_logo }}" />
    <meta name="twitter:domain" content="{{ Request::root() }}" />
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" property="og:title" itemprop="name" content="{{ blogInfo()->blog_name }}">
    <meta name="twitter:description" property="og:description" itemprop="description"
        content="{{ blogInfo()->blog_description }}">
    <meta name="twitter:image" content="{{ blogInfo()->blog_logo }}" />
@endsection
@section('content')


    <div class="row no-gutters-lg">
        <div class="col-12">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                </ol>
                <div class="carousel-inner">
                    @foreach (\App\Models\Hero::all() as $key => $hero)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img class="d-block w-100" src="{{ asset('back/dist/img/hero-image/' . $hero->hero_image) }}"
                                alt="Slide {{ $key }}">
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <div class="col-12">
            <h2 class="section-title mt-4">Postingan terbaru</h2>
        </div>
        <div class="col-lg-8 mb-5 mb-lg-0">
            <div class="row">
                <div class="col-12 mb-4">
                    @if (single_latest_post())
                        <article class="card article-card">
                            <a href="{{ route('read_post', single_latest_post()->post_slug) }}">
                                <div class="card-image">
                                    <div class="post-info"> <span
                                            class="text-uppercase">{{ date_formatter(single_latest_post()->created_at) }}</span>
                                        <span
                                            class="text-uppercase">{{ readDuration(single_latest_post()->post_title, single_latest_post()->post_content) }}
                                            @choice('min|mins', readDuration(single_latest_post()->post_title, single_latest_post()->post_content)) read</span>
                                    </div>
                                    <img loading="lazy" decoding="async"
                                        src="/storage/images/post_images/{{ single_latest_post()->featured_image }}"
                                        alt="Post Thumbnail" class="w-100">
                                </div>
                            </a>
                            <div class="card-body px-0 pb-1">
                                <h2 class="h1"><a class="post-title"
                                        href="{{ route('read_post', single_latest_post()->post_slug) }}">{{ single_latest_post()->post_title }}</a>
                                </h2>
                                <p class="card-text">{!! Str::ucfirst(words(single_latest_post()->post_content, 35)) !!}</p>
                                <div class="content"> <a class="read-more-btn"
                                        href="{{ route('read_post', single_latest_post()->post_slug) }}">Lanjutkan
                                        membaca</a>
                                </div>
                            </div>
                        </article>
                    @endif
                </div>
                @foreach (latest_home_6posts() as $item)
                    <div class="col-md-6 mb-4">
                        <article class="card article-card article-card-sm h-100">
                            <a href="{{ route('read_post', $item->post_slug) }}">
                                <div class="card-image">
                                    <div class="post-info"> <span
                                            class="text-uppercase">{{ date_formatter($item->created_at) }}</span>
                                        <span
                                            class="text-uppercase">{{ readDuration($item->post_title, $item->post_content) }}
                                            @choice('min|mins', readDuration($item->post_title, $item->post_content)) read</span>
                                    </div>
                                    <img loading="lazy" decoding="async"
                                        src="storage/images/post_images/thumbnails/resized_{{ $item->featured_image }}"
                                        alt="Post Thumbnail" class="w-100">
                                </div>
                            </a>
                            <div class="card-body px-0 pb-0">
                                <ul class="post-meta mb-2">
                                    <li> <a
                                            href="{{ route('category_posts', $item->subcategory->slug) }}">{{ $item->subcategory->subcategory_name }}</a>
                                    </li>
                                </ul>
                                <h2><a class="post-title"
                                        href="{{ route('read_post', $item->post_slug) }}">{{ $item->post_title }}</a>
                                </h2>
                                <p class="card-text">{!! Str::ucfirst(words($item->post_content, 30)) !!}</p>
                                <div class="content"> <a class="read-more-btn"
                                        href="{{ route('read_post', $item->post_slug) }}">Lanjutkan membaca</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <div class="widget-blocks">
                <div class="row">
                    {{-- <div class="col-lg-12">
                        <div class="widget">
                            <h2 class="section-title mb-3">Waktu sholat</h2>
                            <div class="widget-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="bg-success" scope="col">#</th>
                                            <th class="bg-success" scope="col">First</th>
                                            <th class="bg-success" scope="col">Last</th>
                                            <th class="bg-success" scope="col">Handle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Mark</td>
                                            <td>Otto</td>
                                            <td>@mdo</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Jacob</td>
                                            <td>Thornton</td>
                                            <td>@fat</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td colspan="2">Larry the Bird</td>
                                            <td>@twitter</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}
                    @if (recomended_posts())
                        <div class="col-lg-12 col-md-6">
                            <div class="widget">
                                <h2 class="section-title mb-3">Saran teratas</h2>
                                <div class="widget-body">
                                    <div class="widget-list">
                                        @include('front.layouts.inc.recomended_list')
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (categories())
                        <div class="col-lg-12 col-md-6">
                            <div class="widget">
                                <h2 class="section-title mb-3">Kategori</h2>
                                <div class="widget-body">
                                    <ul class="widget-list">
                                        @include('front.layouts.inc.categories_list')
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (all_tags() != null)
                        @php
                            $allTagsString = all_tags();
                            $allTagsArray = explode(',', $allTagsString);
                        @endphp
                        <div class="col-lg-12 col-md-6">
                            <div class="widget">
                                <h2 class="section-title mb-3">Tagar</h2>
                                <div class="widget-body">
                                    <ul class="widget-list">
                                        @foreach (array_unique($allTagsArray) as $tag)
                                            <li><a href="{{ route('tag_posts', $tag) }}">#{{ $tag }}<span
                                                        class="ml-auto">(3)</span></a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
@push('stylesheets')
    <style>
        .carousel-item {
            height: 75vh;
            /* Adjust the height of carousel-item as needed */
        }

        .carousel-item img {
            max-width: 100%;
            height: 100%;
            /* Atur tinggi gambar menjadi 100% agar mengisi carousel item */
            display: block;
            object-fit: cover;
            /* Mengatur gambar untuk mengisi ruang tanpa distorsi */
            object-position: center;
            /* Menyimpan bagian penting dari gambar di tengah */
        }

        /* Media query untuk resolusi layar kecil */
        @media (max-width: 767px) {
            .carousel-item {
                height: auto;
                /* Atur tinggi carousel-item menjadi otomatis */
            }
        }
    </style>
@endpush
