

<?php $__env->startSection('content'); ?>
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           <?php echo e(trans('admin.admin'), false); ?> <i class="fa fa-angle-right margin-separator"></i> <?php echo e(trans('admin.members'), false); ?> (<?php echo e($data->total(), false); ?>)

           <a href="<?php echo e(url('panel/admin/member/add')); ?>" class="btn btn-sm btn-success no-shadow pull-right">
	        				<i class="glyphicon glyphicon-plus myicon-right"></i> <?php echo e(trans('misc.add_new')); ?>

	        		</a>

          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	 <?php if(Session::has('info_message')): ?>
		    <div class="alert alert-warning">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		      <i class="fa fa-warning margin-separator"></i>  <?php echo e(Session::get('info_message'), false); ?>

		    </div>
		<?php endif; ?>

		    <?php if(Session::has('success_message')): ?>
		    <div class="alert alert-success">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		       <i class="fa fa-check margin-separator"></i>  <?php echo e(Session::get('success_message'), false); ?>

		    </div>
		<?php endif; ?>

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                  	<?php if( $data->count() != 0 && $data->currentPage() != 1 ): ?>
                  		<a href="<?php echo e(url('panel/admin/members'), false); ?>"><?php echo e(trans('admin.view_all_members'), false); ?></a>
                  	<?php else: ?>
                  		<?php echo e(trans('admin.members'), false); ?>

                  	<?php endif; ?>

                  	</h3>
                  <div class="box-tools">

                 <?php if( $data->total() !=  0 ): ?>
                    <!-- form -->
                    <form role="search" autocomplete="off" action="<?php echo e(url('panel/admin/members'), false); ?>" method="get">
	                 <div class="input-group input-group-sm" style="width: 150px;">
	                  <input type="text" name="q" class="form-control pull-right" placeholder="Search">

	                  <div class="input-group-btn">
	                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
	                  </div>
	                </div>
                </form><!-- form -->
                <?php endif; ?>

                  </div>
                </div><!-- /.box-header -->



                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
               <tbody>

               	<?php if( $data->total() !=  0 && $data->count() != 0 ): ?>
                   <tr>
                      <th class="active">ID</th>
                      <th class="active"><?php echo e(trans('auth.full_name'), false); ?></th>
                      <th class="active"><?php echo e(trans_choice('misc.campaigns_plural', 0), false); ?></th>
                      <th class="active"><?php echo e(trans('admin.date'), false); ?></th>
                      <th class="active"><?php echo e(trans('admin.actions'), false); ?></th>
                    </tr>

                  <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                      <td><?php echo e($user->id, false); ?></td>
                      <td><img src="<?php echo e(asset('public/avatar').'/'.$user->avatar, false); ?>" width="20" height="20" class="img-circle" /> <?php echo e($user->name, false); ?></td>
                      <td><?php echo e($user->campaigns()->count(), false); ?></td>
                      <td><?php echo e(date($settings->date_format, strtotime($user->date)), false); ?></td>
                      <td>

                     <?php if( $user->id <> Auth::user()->id && $user->id <> 1 ): ?>

                   <a href="<?php echo e(route('user.edit', $user->id), false); ?>" class="btn btn-success btn-xs padding-btn">
                      		<?php echo e(trans('admin.edit'), false); ?>

                      	</a>

                   <?php echo Form::open([
			            'method' => 'DELETE',
			            'route' => ['user.destroy', $user->id],
			            'id' => 'form'.$user->id,
			            'class' => 'displayInline'
				        ]); ?>

	            	<?php echo Form::submit(trans('admin.delete'), ['data-url' => $user->id, 'class' => 'btn btn-danger btn-xs padding-btn actionDelete']); ?>

	        	<?php echo Form::close(); ?>


	       <?php else: ?>
	        ------------
                      		<?php endif; ?>

                      		</td>

                    </tr><!-- /.TR -->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php else: ?>
                    <hr />
                    	<h3 class="text-center no-found"><?php echo e(trans('misc.no_results_found'), false); ?></h3>

                    	<?php if( isset( $query ) ): ?>
                    	<div class="col-md-12 text-center padding-bottom-15">
                    		<a href="<?php echo e(url('panel/admin/members'), false); ?>" class="btn btn-sm btn-danger"><?php echo e(trans('auth.back'), false); ?></a>
                    	</div>

                    	<?php endif; ?>
                    <?php endif; ?>

                  </tbody>

                  </table>

                </div><!-- /.box-body -->
              </div><!-- /.box -->
              <?php if( $data->lastPage() > 1 ): ?>
             <?php echo e($data->appends(['q' => $query])->links(), false); ?>

           <?php endif; ?>
            </div>
          </div>

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>

<script type="text/javascript">

$(".actionDelete").click(function(e) {
   	e.preventDefault();

   	var element = $(this);
	var id     = element.attr('data-url');
	var form    = $(element).parents('form');

	element.blur();

	swal(
		{   title: "<?php echo e(trans('misc.delete_confirm'), false); ?>",
		text: "<?php echo e(trans('admin.delete_user_confirm'), false); ?>",
		  type: "warning",
		  showLoaderOnConfirm: true,
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		   confirmButtonText: "<?php echo e(trans('misc.yes_confirm'), false); ?>",
		   cancelButtonText: "<?php echo e(trans('misc.cancel_confirm'), false); ?>",
		    closeOnConfirm: false,
		    },
		    function(isConfirm){
		    	 if (isConfirm) {
		    	 	form.submit();
		    	 	//$('#form' + id).submit();
		    	 	}
		    	 });


		 });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/Fundme/Script/resources/views/admin/members.blade.php ENDPATH**/ ?>