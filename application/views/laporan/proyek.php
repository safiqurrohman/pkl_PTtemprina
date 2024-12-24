<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i> Data submenu</h3>
                        <div class="text-right">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="add_laporan()" title="Add Data"><i class="fas fa-plus"></i> Add</button>
                            <a href="<?php echo base_url('Daftar_laporan/download'); ?>" type="button" class="btn btn-sm btn-outline-info" id="dwn_submenu" title="Download"><i class="fas fa-download"></i> Download</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabelsubmenu" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-info">
                                    <th>Nomer</th>
                                    <th>Divisi</th>
                                    <th>Jenis Pekerjaan</th>
                                    <th>Pekerjaan</th>
                                    <th>Start</th>
                                    <th>Deadline</th>
                                    <th>Finish</th>
                                    <th>User</th>
                                    <th>Sebelum</th>
                                    <th>Tindakan</th>
                                    <th>Setelah</th>
                                    <th>Dibuat</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>

<!-- Modal Hapus-->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="idhapus" id="idhapus">
                <p>Apakah anda yakin ingin menghapus submenu <strong class="text-konfirmasi"> </strong> ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-xs" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-xs" id="konfirmasi">Hapus</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">View Submenu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center" id="md_def">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script type="text/javascript">
    var save_method; //for save method string
    var table;

    $(document).ready(function() {

        //datatables
        table = $("#tabelsubmenu").DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "sEmptyTable": "Data proyek Belum Ada"
            },
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('proyek/ajax_list') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [-1], //last column
                "render": function(data, type, row) {
                    return "<a class=\"btn btn-xs btn-outline-primary\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit_laporan(" + row[14] + ")\"><i class=\"fas fa-edit\"></i></a>\<a class=\"btn btn-xs btn-outline-danger\" href=\"javascript:void(0)\" title=\"Delete\" nama=\"" + row[0] + "\" onclick=\"delete_laporan(" + row[14] + ")\"><i class=\"fas fa-trash\"></i></a>";
                },
                "orderable": false,
            }, ],

        });

        //set input/textarea/select event when change value, remove class error and remove text help block 
        $("input").change(function() {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
            $(this).removeClass('is-invalid');
        });
        $("textarea").change(function() {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
            $(this).removeClass('is-invalid');
        });
        $("select").change(function() {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
            $(this).removeClass('is-invalid');
        });

    });

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });


    //view
    // $(".v_submenu").click(function(){
    function vlaporan(id) {
        $('.modal-title').text('View laporan');
        $("#modal-default").modal();
        $.ajax({
            url: '<?php echo base_url('proyek/viewlaporan'); ?>',
            type: 'post',
            data: 'table=tbl_submenu&id=' + id,
            success: function(respon) {

                $("#md_def").html(respon);
            }
        })


    }


    function delete_laporan(id) {

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo site_url('proyek/delete'); ?>",
                    type: "POST",
                    data: "id_plan=" + id,
                    cache: false,
                    dataType: 'json',
                    success: function(respone) {
                        if (respone.status == true) {
                            reload_table();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Delete Error!!.'
                            });
                        }
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                )
            }
        })
    }



    function add_laporan() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Tambah Proyek'); // Set Title to Bootstrap modal title
    }

    function edit_laporan(id) {
        save_method = 'update';
        $('#form')[0].reset(); // Reset form
        $('.form-group').removeClass('has-error'); // Hapus kelas error
        $('.help-block').empty(); // Hapus pesan error

        // var id = 1 ;
        console.log(`<?php echo site_url('proyek/editlaporan') ?>/` + id);
        // Ambil data menggunakan Ajax
        $.ajax({
            url: "<?php echo site_url('proyek/editlaporan') ?>/" + id, // Pastikan URL ini sesuai dengan rute di controller Anda
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // ID laporan
                // ID laporan
                $('[name="id_plan"]').val(data.id_plan);
                $('[name="id_divisi"]').val(data.id_divisi);
                $('[name="jenis_pekerjaan"]').val(data.jenis_pekerjaan);
                $('[name="pekerjaan"]').val(data.pekerjaan);
                $('[name="mulai"]').val(data.mulai);
                $('[name="deadline"]').val(data.deadline);
                $('[name="berakhir"]').val(data.berakhir);
                $('[name="user"]').val(data.user);
                $('[name="sebelum"]').val(data.sebelum);
                $('[name="tindakan"]').val(data.tindakan);
                $('[name="setelah"]').val(data.setelah);
                $('[name="dibuat"]').val(data.dibuat);
                $('[name="keterangan"]').val(data.keterangan);
                $('[name="status"]').val(data.status);

                // Tampilkan modal dengan data yang sudah diisi
                $('#modal_form').modal('show');
                $('.modal-title').text('Edit Laporan'); // Ubah judul modal sesuai
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });

    }

    function save() {
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable 
        var url;

        if (save_method == 'add') {
            url = "<?php echo site_url('proyek/insert') ?>";
        } else {
            url = "<?php echo site_url('proyek/update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data) {

                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                    Toast.fire({
                        icon: 'success',
                        title: 'Success!!.'
                    });
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid');
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]).addClass('invalid-feedback');
                    }
                }
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 


            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 

            }
        });
    }
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">Person Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="card-body">

                        <!-- ID Divisi -->
                        <div class="form-group row">
                            <label for="id_divisi" class="col-sm-3 col-form-label">Divisi</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="id_divisi" id="id_divisi">
                                    <option value="" selected disabled>Pilih Divisi</option>
                                    <?php
                                    foreach ($nama_divisi as $divisi):
                                        echo "<option value='$divisi->id_divisi'>$divisi->nama_divisi</option>";
                                    endforeach; ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Jenis Pekerjaan -->
                        <div class="form-group row">
                            <label for="jenis_pekerjaan" class="col-sm-3 col-form-label">Jenis Pekerjaan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="jenis_pekerjaan" id="jenis_pekerjaan" placeholder="Jenis Pekerjaan">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Pekerjaan -->
                        <div class="form-group row">
                            <label for="pekerjaan" class="col-sm-3 col-form-label">Pekerjaan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="pekerjaan" id="pekerjaan" placeholder="Pekerjaan">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Mulai -->
                        <div class="form-group row">
                            <label for="mulai" class="col-sm-3 col-form-label">Mulai</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="mulai" id="mulai">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Deadline -->
                        <div class="form-group row">
                            <label for="deadline" class="col-sm-3 col-form-label">Deadline</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="deadline" id="deadline">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Berakhir -->
                        <div class="form-group row">
                            <label for="berakhir" class="col-sm-3 col-form-label">Berakhir</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="berakhir" id="berakhir">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- User -->
                        <div class="form-group row">
                            <label for="user" class="col-sm-3 col-form-label">User</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="user" id="user" placeholder="Nama User">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Sebelum -->
                        <div class="form-group row">
                            <label for="sebelum" class="col-sm-3 col-form-label">Sebelum</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="sebelum" id="sebelum" placeholder="Deskripsi Sebelum"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Tindakan -->
                        <div class="form-group row">
                            <label for="tindakan" class="col-sm-3 col-form-label">Tindakan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="tindakan" id="tindakan" placeholder="Deskripsi Tindakan"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Setelah -->
                        <div class="form-group row">
                            <label for="setelah" class="col-sm-3 col-form-label">Setelah</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="setelah" id="setelah" placeholder="Deskripsi Setelah"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Dibuat -->
                        <div class="form-group row">
                            <label for="dibuat" class="col-sm-3 col-form-label">Dibuat</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="dibuat" id="dibuat" placeholder="Dibuat Oleh">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="form-group row">
                            <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row">
                            <label for="status" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status" id="status">
                                    <option value="" selected disabled>Pilih Status</option>
                                    <option value="Start">Start</option>
                                    <option value="On Progres">On Progres</option>
                                    <option value="Done">Done</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- /.modal -->
<!-- End Bootstrap modal -->