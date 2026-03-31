@extends('layouts.admin')

@section('title', 'Manajemen Pengguna | Lookbook')
@section('page_title', 'Manajemen Pengguna')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Manajemen Pengguna</li>
@endsection

@section('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
  <!-- SweetAlert2 / Toastr -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card card-outline card-info">
        <div class="card-header">
          <h3 class="card-title">Daftar Petugas / Pengguna Aplikasi</h3>
          <div class="card-tools">
            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-add">
              <i class="fas fa-plus"></i> Tambah Pengguna
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="userTable" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead class="bg-dark text-white">
              <tr>
                <th style="width: 5%">No</th>
                <th>Nama Petugas</th>
                <th>Nomor Induk Karyawan (NIK)</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $index => $user)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td><i class="fas fa-user text-muted mr-1"></i> <strong>{{ $user->name }}</strong></td>
                  <td>{{ $user->nik }}</td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-info btn-edit" data-id="{{ $user->id }}"><i class="fas fa-edit"></i>
                      Edit</button>
                    <!-- Jangan biarkan user menghapus dirinya sendiri -->
                    @if(Auth::id() !== $user->id)
                      <button class="btn btn-sm btn-danger btn-delete" data-url="{{ route('users.destroy', $user->id) }}"><i
                          class="fas fa-trash"></i> Hapus</button>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer text-muted text-sm">
          <i>* Pengguna dapat menggunakan NIK untuk login ke aplikasi. Peringatan: jangan menghapus akun yang telah
            membuat log banyak karena bisa memutuskan relasi data.</i>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL ADD USER -->
  <div class="modal fade" id="modal-add">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Tambah Pengguna</h4>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Tuliskan nama lengkap..." required>
            </div>
            <div class="form-group">
              <label>NIK (Login ID) <span class="text-danger">*</span></label>
              <input type="text" name="nik" class="form-control" placeholder="Tuliskan NIK..." required>
            </div>
            <div class="form-group">
              <label>Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" placeholder="Kata sandi..." minlength="5"
                required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Simpan Pengguna</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL EDIT USER -->
  <div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title text-dark"><i class="fas fa-edit mr-2"></i>Edit Pengguna</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="POST" id="form-edit">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="text-center" id="edit-loading" style="display:none;">
              <i class="fas fa-spinner fa-spin fa-2x"></i> Memuat data...
            </div>

            <div id="edit-content">
              <div class="form-group">
                <label>Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
              </div>
              <div class="form-group">
                <label>NIK (Login ID) <span class="text-danger">*</span></label>
                <input type="text" name="nik" id="edit_nik" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" class="form-control"
                  placeholder="Kosongkan jika tidak ingin ganti sandi..." minlength="5">
                <small class="text-muted">Isi hanya jika ingin mengubah password akun ini.</small>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-warning" id="btn-update-submit"><i class="fas fa-save"></i> Update
              Profil</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <!-- DataTables  & Plugins -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Global event delegation (persists across Turbo visits)
    $(document).on('click', '.btn-edit', function () {
      let id = $(this).data('id');
      let formAction = "{{ url('/users') }}/" + id;
      $('#form-edit').attr('action', formAction);

      $('#modal-edit').modal('show');
      $('#edit-content').hide();
      $('#edit-loading').show();
      $('#btn-update-submit').prop('disabled', true);

      $.get("{{ url('/users') }}/" + id, function (data) {
        $('#edit_name').val(data.name);
        $('#edit_nik').val(data.nik);

        $('#edit-loading').hide();
        $('#edit-content').fadeIn();
        $('#btn-update-submit').prop('disabled', false);
      }).fail(function () {
        $('#modal-edit').modal('hide');
        Swal.fire({ icon: 'error', title: 'Gagal mengambil data dari server.' });
      });
    });

    $(document).on('click', '.btn-delete', function () {
      let url = $(this).data('url');
      Swal.fire({
        title: 'Hapus Pengguna ini?',
        text: "Pengguna tidak akan bisa login lagi!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: url,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
              Swal.fire({ icon: 'success', title: response.success }).then(() => {
                location.reload();
              });
            },
            error: function () {
              Swal.fire({ icon: 'error', title: 'Terjadi kesalahan. Pastikan user ini tidak terkait dengan Logbook.' });
            }
          });
        }
      });
    });

    $(function () {
      if ($('#userTable').length > 0) {
        $('#userTable').DataTable({
          "paging": true, "lengthChange": true, "searching": true, "ordering": true, "info": true, "autoWidth": false, "responsive": true,
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