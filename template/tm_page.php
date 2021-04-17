<div>
	<div class="avatarka">
		<?php avatarka($row); ?>
	</div>

	<div class="under_img">
		<?php set_under_img_links($_GET['page_id']); ?>
		<?php set_friends_list(); ?>
	</div>
	<table>
		<tr>
			<td colspan="2"><b><?php echo $row['first_name'].' '.$row['last_name']?></b></td>
		</tr>
		<tr>
			<td>Статус:</td>
			<td><?php echo $row['status']?></td>
		</tr>
		<tr>
			<td>Дата рождения:</td>
			<td><?php echo $row['birthday']?></td>
		</tr>
		<tr>
			<td>Город:</td>
			<td><?php echo $row['city']?></td>
		</tr>
		<tr>
			<td>О себе:</td>
			<td><textarea class="about_me" name="about_me" readonly="readonly"><?php echo $row['about_me']?></textarea></td>
		</tr>
	</table>
</div>