<div>
    <div class="row row-cards">
        @forelse ($heros as $hero)
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <img src="{{ asset('back/dist/img/hero-image/' . $hero->hero_image) }}" alt=""
                        class="card-img-top">
                    <div class="d-flex">
                        <a href="{{ route('author.edit-hero', ['hero_id' => $hero->id]) }}" class="card-btn">Edit</a>
                        <a href="" wire:click.prevent='deleteHero({{ $hero->id }})' class="card-btn">Hapus</a>
                    </div>
                </div>
            </div>
        @empty
            <span class="text-danger">No Hero(s) found.</span>
        @endforelse
    </div>
    <div class="d-block mt-2">
        {{ $heros->links('livewire::simple-bootstrap') }}
    </div>
</div>
