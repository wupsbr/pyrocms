	<table class="table table-striped" cellspacing="0">
		<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
				<th><?php echo lang('blog:post_label'); ?></th>
				<th class="collapsible"><?php echo lang('blog:category_label'); ?></th>
				<th class="collapsible"><?php echo lang('blog:date_label'); ?></th>
				<th class="collapsible"><?php echo lang('blog:written_by_label'); ?></th>
				<th><?php echo lang('blog:status_label'); ?></th>
				<th><!-- Actions --></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($blog as $post) : ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $post->id); ?></td>
					<td><?php echo $post->title; ?></td>
					<td class=""><?php echo $post->category_title; ?></td>
					<td class=""><?php echo format_date($post->created_on); ?></td>
					<td class="">
					<?php if (isset($post->display_name)): ?>
						<?php echo anchor('user/'.$post->username, $post->display_name, 'target="_blank"'); ?>
					<?php else: ?>
						<?php echo lang('blog:author_unknown'); ?>
					<?php endif; ?>
					</td>
					<td><?php echo lang('blog:'.$post->status.'_label'); ?></td>
					<td>
	          <?php if($post->status=='live') : ?>
	              <?php echo anchor('blog/' . date('Y/m', $post->created_on). '/'. $post->slug, lang('global:view'), 'class="glyphicon-share" target="_blank"');?>
	          <?php else: ?>
	              <?php echo anchor('blog/preview/' . $post->preview_hash, lang('global:preview'), 'class="glyphicon-share" target="_blank"');?>
	          <?php endif; ?>
	          <?php echo anchor('admin/blog/edit/' . $post->id, lang('global:edit'), 'class="glyphicon-edit"'); ?>
	          <?php echo anchor('admin/blog/delete/' . $post->id, lang('global:delete'), array('class'=>'glyphicon-trash')); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="table_action_buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete', 'publish'))); ?>
	</div>