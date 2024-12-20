@extends('layouts.template')
@section('title', 'Anco')
@section('content')
    <div class="card">
        <div class="d-flex justify-content-betwen">
        <div class="card-header">Data Anco</div>
                <button class="btn btn-primary mt-auto ml-auto mr-2 rounded-10"
                    onclick="window.location.href='{{ route('user.anco.create') }}'">
                            Tambah Data
                </button>
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row">
                    @foreach ($anco as $detailAnco)
                        <div class="col-md-6 mb-4">
                            <div class="card border border-primary" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-8 d-flex flex-column">
                                       <a href="{{ route('user.anco.show', $detailAnco->id_anco) }}" class="text-decoration-none text-dark">
                                       <div class="card-body flex-grow-1">
                                            <h5 class="card-title">{{ $detailAnco->kd_anco }}</h5>
                                            <p class="card-text m-0">Fase Kolam : {{ $detailAnco->faseKolam->kd_fase_tambak }}</p>
                                            <p class="card-text">Catatan : {{ $detailAnco->catatan }} </p>
                                            <p class="card-text">
                                                <small class="text-body-secondary">
                                                    Terakhir Diperbarui :
                                                    {{ \Carbon\Carbon::parse($detailAnco->updated_at)->translatedFormat('l, j F Y') }}
                                                </small>
                                            </p>
                                        </div>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
@endpush