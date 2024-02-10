<?php

?>
<!-- page/modal /-->

<div class="modal fade" id="my_dialog">
    <div class="modal-dialog">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $title;?></h4>
				<button type="button" class="close button edit" id='close-modal' data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
        <div class="modal-content">
            <div class="modal-body">
                <!--page/modal -->
                <?php $this->load->view($target);?>
            </div>
            <div class="modal-footer"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
