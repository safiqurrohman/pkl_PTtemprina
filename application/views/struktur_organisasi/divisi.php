    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h3 class="card-title"><i class="fa fa-list text-blue"></i>Daftar Divisi</h3>
                            <div class="text-right">

                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="add_divisi()" title="Add Data"><i class="fas fa-plus"></i> Add</button>
                                <a href="<?php echo base_url('menu/download') ?>" type="button" class="btn btn-sm btn-outline-info" id="dwn_menu" target="_blank" title="Download"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="divisi" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr class="bg-info">
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Divisi</th>
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
                    <p>Apakah anda yakin ingin menghapus menu <strong class="text-konfirmasi"> </strong> ?</p>
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
                    <h4 class="modal-title ">View Menu</h4>
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

            table = $("#divisi").DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "sEmptyTable": "Data menu Belum Ada"
                },
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.

                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('divisi/ajax_list') ?>",
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                //Set column definition initialisation properties.
                "columnDefs": [{
                        "targets": [-1], //last column
                        "render": function(data, type, row) {

                            return "<a class=\"btn btn-xs btn-outline-primary\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit_divisi(" + row[3] + ")\"><i class=\"fas fa-edit\"></i></a>\<a class=\"btn btn-xs btn-outline-danger\" href=\"javascript:void(0)\" title=\"Delete\" nama=\"" + row[0] + "\" onclick=\"delete_divisi(" + row[3] + ")\"><i class=\"fas fa-trash\"></i></a>";

                        },

                        "orderable": false, //set not orderable
                    },

                ],
            });
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
        function vmenu(id) {
            $('.modal-title').text('View Menu');
            $("#modal-default").modal('show');
            $.ajax({
                url: '<?php echo base_url('menu/viewmenu'); ?>',
                type: 'post',
                data: 'table=tbl_menu&id=' + id,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }

        //delete
        function delete_divisi(id) {

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
                        url: "<?php echo site_url('divisi/delete'); ?>",
                        type: "POST",
                        data: "id_divisi=" + id,
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




        function add_divisi() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Add Divisi'); // Set Title to Bootstrap modal title
        }

        function edit_divisi(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('divisi/editdivisi') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    $('[name="id_divisi"]').val(data.id_divisi);
                    $('[name="nama"]').val(data.nama);
                    $('[name="nama_divisi"]').val(data.nama_divisi);
                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Edit Divisi'); // Set title to Bootstrap modal title

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
                url = "<?php echo site_url('divisi/insert') ?>";
            } else {
                url = "<?php echo site_url('divisi/update') ?>";
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
                            $('[name="' + data.inputerror[i] + '"]').closest('.kosong').append('<span></span>');
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
                        <input type="hidden" value="" name="id_menu" />
                        <div class="card-body">
                            <div class="form-group row ">
                                <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                <div class="col-sm-9 kosong">
                                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama">
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="nama_divisi" class="col-sm-3 col-form-label">Divisi</label>
                                <div class="col-sm-9 kosong">
                                    <input type="text" class="form-control" name="nama_divisi" id="nama_divisi" placeholder="Nama Divisi">
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