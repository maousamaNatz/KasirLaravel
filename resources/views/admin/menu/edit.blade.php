@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Edit Menu</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.makanans.update', $makanan->id_masakan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama_masakan">Nama Menu</label>
                    <input type="text" class="form-control @error('nama_masakan') is-invalid @enderror"
                           id="nama_masakan" name="nama_masakan"
                           value="{{ old('nama_masakan', $makanan->nama_masakan) }}" required>
                    @error('nama_masakan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control @error('harga') is-invalid @enderror"
                           id="harga" name="harga"
                           value="{{ old('harga', $makanan->harga) }}" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status_masakan">Status</label>
                    <select class="form-control @error('status_masakan') is-invalid @enderror"
                            id="status_masakan" name="status_masakan" required>
                        <option value="tersedia" {{ old('status_masakan', $makanan->status_masakan) == 'tersedia' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="tidak_tersedia" {{ old('status_masakan', $makanan->status_masakan) == 'tidak_tersedia' ? 'selected' : '' }}>
                            Tidak Tersedia
                        </option>
                    </select>
                    @error('status_masakan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Menu</button>
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
