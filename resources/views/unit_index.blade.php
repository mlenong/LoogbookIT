@extends('layouts.admin')

@section('title', 'Manajemen Unit | Lookbook')
@section('page_title', 'Manajemen Unit/Gedung')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Manajemen Unit</li>
@endsection

@section('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
    <div class="card card-outline card-success">
        <div class="card-header">
        <h3 class="card-title">Daftar Lokasi/Unit Tersedia</h3>
        <div class="card-tools">
            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-add">
                <i class="fas fa-plus"></i> Tambah Unit
            </button>
        </div>
        </div>
        <div class="card-body">
        <table id="unitTable" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead class="bg-dark text-white">
            <tr>
                <th style="width: 5%">No</th>
                <th>Nama Gedung / Ruangan</th>
                <th>Keterangan / Fungsi</th>
                <th class="text-center">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($units as $index => $unit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><i class="fas fa-building text-success mr-1"></i> <strong>{{ $unit->nama }}</strong></td>
                <td>{{ $unit->keterangan ?? '-' }}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-info btn-edit" data-id="{{ $unit->id }}"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn btn-sm btn-danger btn-delete" data-url="{{ route('units.destroy', $unit->id) }}"><i class="fas fa-trash"></i> Hapus</button>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        <div class="card-footer text-muted text-sm">
            <i>* Unit ini akan tampil dalam opsi pilihan Dropdown di form Perawatan & Pembersihan.</i>
        </div>
    </div>
    </div>
</div>

<!-- MODAL ADD -->
<div class="modal fade" id="modal-add">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h4 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Tambah Unit</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('units.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="form-group">
                <label>Nama Unit / Gedung <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="Contoh: Gedung A" required>
            </div>
            <div class="form-group">
                <label>Keterangan / Fungsi</label>
                <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Rawat Inap (Opsional)">
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Unit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modal-edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h4 class="modal-title text-dark"><i class="fas fa-edit mr-2"></i>Edit Unit</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="text-center" id="edit-loading" style="display:none;">
                <i class="fas fa-spinner fa-spin fa-2x"></i> Memuat...
            </div>
            <div id="edit-content">
                <div class="form-group">
                    <label>Nama Unit / Gedung <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Keterangan / Fungsi</label>
                    <input type="text" name="keterangan" id="edit_keterangan" class="form-control">
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-warning" id="btn-update-submit"><i class="fas fa-save"></i> Simpan Pebaikan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Global event delegation (persists across Turbo visits)
    $(document).on('click', '.btn-edit', function() {
        let id = $(this).data('id');
        $('#form-edit').attr('action', "{{ url('/units') }}/" + id);
        $('#modal-edit').modal('show');
        $('#edit-content').hide();
        $('#edit-loading').show();
        
        $.get("{{ url('/units') }}/" + id, function(data) {
            $('#edit_nama').val(data.nama);
            $('#edit_keterangan').val(data.keterangan);
            $('#edit-loading').hide();
            $('#edit-content').fadeIn();
        }).fail(() => Swal.fire({ icon: 'error', title: 'Gagal mengambil data.' }));
    });

    $(document).on('click', '.btn-delete', function() {
        let url = $(this).data('url');
        Swal.fire({
            title: 'Hapus Unit ini?', icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!'
        }).then((res) => {
            if (res.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                    success: function() { 
                        location.reload();
                    }
                });
            }
        });
    });

    $(function() {
        if ($('#unitTable').length > 0) {
            $('#unitTable').DataTable({
                "responsive": true, "autoWidth": false,
                "destroy": true, // Critical for standard load
                "language": { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
            });

            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            @if(session('success')) Toast.fire({ icon: 'success', title: '{{ session('success') }}' }); @endif
            @if($errors->any()) Toast.fire({ icon: 'error', title: '{{ $errors->first() }}' }); @endif
        }
    });
</script>
@endsection
