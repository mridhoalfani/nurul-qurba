@extends('front.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Welcome to website')
@section('content')

    <main>
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumbs mb-4"> <a href="/">Beranda</a>
                            <span class="mx-1">/</span> <a href="#!">Kontak</a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="pr-0 pr-lg-4">
                            <div class="content">Memiliki pertanyaan atau ingin berkontribusi pada Masjid Nurul Qurba?
                                Jangan ragu untuk menghubungi kami! Kami siap melayani Anda dengan sepenuh hati.
                                <div class="mt-5">
                                    <p class="h3 mb-3 font-weight-normal"><a class="text-dark"
                                            href="mailto:hello@reporter.com">nurulqurbaa@gmail.com</a>
                                    </p>
                                    <p class="mb-3"><a class="text-dark" href="tel:+211234565523">+211234565523</a>
                                    </p>
                                    <p class="mb-2">Jl. Antara, Kota Bengkalis, Riau 28711</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-4 mt-lg-0">
                        <div class="alert alert-success alert-dismissible fade show my-alert d-none" role="alert">
                            <strong>Terima kasih!</strong> Pesan anda terkirim.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="#!" class="row" name="nurul-qurba-kontak-form">
                            <div class="col-md-6">
                                <input type="text" class="form-control mb-4" placeholder="Nama" name="nama"
                                    id="name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control mb-4" placeholder="Email" name="email"
                                    id="email" required>
                            </div>
                            <div class="col-12">
                                <textarea name="pesan" id="message" class="form-control mb-4" placeholder="Tulis pesan anda" rows="5"
                                    required></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-outline-primary btn-kirim" type="submit">Kirim pesan</button>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-outline-primary btn-loading d-none" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading ..
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection

@push('scripts')
    <script>
        const scriptURL =
            'https://script.google.com/macros/s/AKfycbz58vlDn0P8nxjNSIfL7jozPab4IR7e2jg--GyjwpEi5zyQvdQQRqyNEGtIaE0KDUES/exec'
        const form = document.forms['nurul-qurba-kontak-form']
        const btnKirim = document.querySelector('.btn-kirim');
        const btnLoading = document.querySelector('.btn-loading');
        const myAlert = document.querySelector('.my-alert');

        form.addEventListener('submit', e => {
            e.preventDefault()

            btnLoading.classList.toggle('d-none');
            btnKirim.classList.toggle('d-none');

            fetch(scriptURL, {
                    method: 'POST',
                    body: new FormData(form)
                })
                .then(response => {
                    btnLoading.classList.toggle('d-none');
                    btnKirim.classList.toggle('d-none');

                    myAlert.classList.toggle('d-none');
                    form.reset();

                    console.log('Success!', response)
                })
                .catch(error => console.error('Error!', error.message))
        })
    </script>
@endpush
