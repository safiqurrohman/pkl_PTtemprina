<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i>Plaining Pekerjaan</h3>
                        <div class="text-right">
                            <!-- <button type="button" class="btn btn-sm btn-outline-primary" onclick="add_submenu()" title="Add Data"><i class="fas fa-plus"></i> Add</button> -->
                            <a href="<?php echo base_url('submenu/download'); ?>" type="button" class="btn btn-sm btn-outline-info" id="dwn_submenu" title="Download"><i class="fas fa-download"></i> Download</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <img src="<?= base_url() ?>assets/foto/logo/struktur.jpg" alt="hirarki organisasi" style="width: 1450px; height: 1100px;">
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