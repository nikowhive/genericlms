<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<div class="right-side--fullHeight  ">

	<div class="row w-100 ">
		<?php $this->load->view("components/course_menu"); ?>

		<div class="col-md-12">
			<div class="container container--sm">

				<div class="box" style="margin-top: 15px">
					<div class="box-header">
						<h3 class="box-title"><i class="fa icon-classwork"></i> <?= $this->lang->line('panel_title') ?></h3>
					</div><!-- /.box-header -->
					<!-- form start -->
					<div class="box-body">
						<div class="row">
							<div class="col-lg-12">
								<div id="hide-table">
									<!-- <?php if ($usertypeID == 2) { ?>
										Update Status: <button class="btn btn-xs btn-primary" id="classworkCheck">Checked</button>
										<br><br>
									<?php } ?> -->
									<table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
										<thead>
											<tr>

												<th><?= $this->lang->line('slno') ?></th>
												<th><?= $this->lang->line('classwork_photo') ?></th>
												<th><?= $this->lang->line('classwork_student') ?></th>
												<th><?= $this->lang->line('classwork_roll') ?></th>
												<th><?= $this->lang->line('classwork_section') ?></th>
												<th><?= $this->lang->line('classwork_submission') ?></th>
												<th>Status</th>
												<th><?= $this->lang->line('action') ?></th>
												
													<?php if ($usertypeID == 2) { ?>
														<th>
														Action
														<div class="onoffswitch-small" id="onoffswitch">
															<input type="checkbox" id="allcheck" class="onoffswitch-small-checkbox" name="allcheck" value="1">
															<label for="allcheck" class="onoffswitch-small-label">
																<span class="onoffswitch-small-inner"></span>
																<span class="onoffswitch-small-switch"></span>
															</label>
														</div>
														</th>
													<?php } ?>
												
											</tr>
										</thead>
										<tbody>
											<?php if (customCompute($classworkanswers)) {
												$i = 1;
												foreach ($classworkanswers as $classworkanswer) { ?>
													<tr>
														
														<td data-title="<?= $this->lang->line('slno') ?>">
															<?= $i ?>
														</td>
														<td data-title="<?= $this->lang->line('classwork_photo') ?>">
															<?= profileimage($classworkanswer->photo,56) ?>
														</td>
														<td data-title="<?= $this->lang->line('classwork_student') ?>">
															<?= $classworkanswer->srname ?>
														</td>
														<td data-title="<?= $this->lang->line('classwork_roll') ?>">
															<?= $classworkanswer->srroll ?>
														</td>
														<td data-title="<?= $this->lang->line('classwork_section') ?>">
															<?= $classworkanswer->section ?>
														</td>
														<td data-title="<?= $this->lang->line('classwork_submission') ?>">
															<?= date('d M Y', strtotime($classworkanswer->answerdate)) ?>
														</td>
														<td id="status<?= $classworkanswer->classworkanswerID ?>"><?= $classworkanswer->status ?></td>
														<td data-title="<?= $this->lang->line('action') ?>">
															<a href="javascript:void(0)" class="btn btn-xs btn-primary viewClassworkAnswer" data-toggle="modal" onclick="" data-id="<?php echo $classworkanswer->classworkanswerID ?>">
																View
															</a>
															<?php if ($usertypeID == 2) { ?>
																<button type="button" class="btn btn-xs btn-primary remarksModal" data-toggle="modal" data-id="<?php echo $classworkanswer->classworkanswerID ?>">
																	Remarks
																</button>
															<?php } ?>
															<?php if ($usertypeID == 3 && $classworkanswer->remarks != '') { ?>
																<button type="button" class="btn btn-xs btn-primary remarksModal" data-toggle="modal" data-id="<?php echo $classworkanswer->classworkanswerID ?>">
																	Remarks
																</button>
															<?php } ?>
														</td>
														<?php if ($usertypeID == 2) { ?>
															<td>
																<div class="onoffswitch-small">
																	<input type="checkbox" id="checkinput<?= $classworkanswer->classworkanswerID; ?>" class="onoffswitch-small-checkbox checkinput" name="checkinput" value="<?php echo $classworkanswer->classworkanswerID; ?>" <?php if ($classworkanswer->status === 'checked') echo "checked='checked'"; ?>>
																	<label for="checkinput<?= $classworkanswer->classworkanswerID; ?>" class="onoffswitch-small-label">
																		<span class="onoffswitch-small-inner"></span>
																		<span class="onoffswitch-small-switch"></span>
																	</label>
																</div>
															</td>
														<?php } ?>
													
													</tr>
											<?php $i++;
												}
											} ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<?php if ($usertypeID == 2) { ?>
						<h5 class="modal-title" id="exampleModalLabel">Add Remarks</h5>
					<?php } ?>
				</div>
				<div class="modal-body" id="remark_ajax_modal_content">

				</div>

				<div class="modal-footer">
					<?php if ($usertypeID == 2) { ?>
						<button type="submit" class="btn btn-primary" id="studentRemarkBtn">Save</button>
					<?php } ?>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		// $('#allcheck').click(function() {
		// 	$(':checkbox.checkinput').prop('checked', this.checked);
		// });

		$('#allcheck').click(function() {
			$(':checkbox.checkinput').prop('checked', this.checked);
			var lenght = $(":checkbox.checkinput:checked").length;
			if (lenght == 0) {
				showToastError('Please check atleast one to update classwork answer status.');
				return false;
			}
			var ids = [];
			$.each($(":checkbox.checkinput:checked"), function() {
				ids.push($(this).val());
			});
			var ids = ids.join(",");

			$.ajax({
				type: 'GET',
				url: "<?= base_url('classwork/updateClassworkStatus') ?>",
				data: {
					'ids': ids,
				},
				success: function(data) {
					if (data) {
						var numbers = data.split(',');
						for (var i = 0; i < numbers.length; i++) {
							$('#status' + numbers[i]).html('checked');
						}
						$('#onoffswitch').hide();
						showToast('Success');
					} else {
						showToastError('Fail');
					}
				}
			});
		});

		$('.checkinput').click(function() {
			id = $(this).val();
			$.ajax({
				type: 'GET',
				url: "<?= base_url('classwork/updateSingleClassworkStatus') ?>",
				data: {
					'id': id,
				},
				success: function(data) {
					if (data) {
						console.log(data);
						result= JSON.parse(data)
						if(result.status=="pending"){
							$(':checkbox#allcheck').prop('checked', false);
						}
							$('#status'+id).html(result.status);
							$('#onoffswitch').show();
						showToast('Success');
					} else {
						showToastError('Fail');
					}
				}
			});
		});

		$(".viewClassworkAnswer").on("click", function() {
			var usertype = <?php echo $usertypeID ?>;
			id = $(this).data("id");
			$("#view_ajax_modal_content").empty();
			$.ajax({
				type: "POST",
				url: BASE_URL + "classwork/viewClassworkAnswerByAjax",
				dataType: "html",
				data: {
					id: id
				},
				success: function(data) {
					console.log(data);
					$("#viewmodal").modal("show");
					$("#view_ajax_modal_content").html(data);
					if (usertype == 2) {
						if ($('#status' + id).html() == 'pending') {
							$('#status' + id).html('viewed');
						}
					}
				},
			});
		});

		$(".remarksModal").on("click", function() {
			var id = $(this).data("id");
			$.ajax({
				type: "POST",
				url: BASE_URL + "classwork/loadRemarkForm",
				dataType: "html",
				data: {
					id: id
				},
				success: function(data) {
					console.log(data);
					$("#remarksModal").modal("show");
					$("#remark_ajax_modal_content").html(data);
				},
			});
		});

		$('#studentRemarkBtn').click(function(event) {
			event.preventDefault();
			var id = $('#classworkanswerID').val();
			if ($('#comment').val() == '') {
				showToastError('Comment is empty.');
				return false;
			}
			var formValues = $('#studentRemarkForm').serialize();
			$.ajax({
				type: "POST",
				url: BASE_URL + "classwork/addRemarks",
				data: formValues,
				success: function(data) {
					if (data) {
						showToast('Success');
						// $('#status' + id).html('Checked');
						$('#remarksModal').modal("hide");
					} else {
						showToastError('Fail');
					}

				},
			});

		});
	</script>