<?php
if(function_exists('get_multi_mr_redirects')){
	$options = get_multi_mr_redirects();
}
?>
<div class="container">
	<div class="row legend">
		<div class="col-xs-12">
			<h4>Mobile Redirects</h4>
		</div>
	</div><div class="row">
		<div class="col-xs-12">
			<form id="mobile-redirects">
				<input type="hidden" name="action" value="multi_mr_update_redirects"/>
				<div class="list">
					<?php if(!count($options)):?>
						<div class="col-sm-8 flex-center">
							<div class="form-group col-xs-11">
								<div class="row">
									<div class="col-xs-12 col-sm-6">
										<label>Request Url</label>
										<input type="url" class="form-control" name="request_url[]" placeholder="http://source.com" required>
									</div>
									<div class="col-xs-12 col-sm-6">
										<label>Redirect Url</label>
										<input type="url" class="form-control" name="redirect_url[]" placeholder="http://destination.com" required>
									</div>
								</div>
							</div>
							<div class="col-xs-1">
								<a href="javascipt:void(0)" class="actions remove-row" title="Delete Row"><i class="fa fa-2x fa-minus-circle" aria-hidden="true"></i></a>
								<a href="javascipt:void(0)" class="actions add-row" title="Add Row"><i class="fa fa-2x fa-plus-circle" aria-hidden="true"></i></a>
							</div>
						</div>
					<?php else: ?>
						<?php foreach ($options as $option): ?>
							<div class="col-sm-8 flex-center">
								<div class="form-group col-xs-11">
									<div class="row">
										<div class="col-xs-12 col-sm-6">
											<label>Request Url</label>
											<input type="url" class="form-control" name="request_url[]" value="<?php echo $option['request'];?>" placeholder="http://source.com" required>
										</div>
										<div class="col-xs-12 col-sm-6">
											<label>Redirect Url</label>
											<input type="url" class="form-control" name="redirect_url[]" value="<?php echo $option['redirect'];?>" placeholder="http://destination.com" required>
										</div>
									</div>
								</div>
								<div class="col-xs-1">
									<a href="javascipt:void(0)" class="actions remove-row" title="Delete Row"><i class="fa fa-2x fa-minus-circle" aria-hidden="true"></i></a>
									<a href="javascipt:void(0)" class="actions add-row" title="Add Row"><i class="fa fa-2x fa-plus-circle" aria-hidden="true"></i></a>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<div class="col-sm-8">
					<div class="col-xs-12 text-right">
						<input type="submit" class="btn btn-info" value="Update">
					</div>
				</div>
			</form>
		</div>
		<div class="col-xs-12 <?php if(!count($options)){ echo 'hidden'; }?>">
			<h4>Remove all Redirects</h4>
			<button class="btn btn-warning" id="remove-all">Clear all redirects</button>
		</div>
	</div>
</div>
