<div>
	<form enctype="multipart/form-data" action="/index.php/page_edit?page_id=<?php echo $_GET['page_id']?>&edit=1" method="post">
	<table>
		<?php
		if ($_SESSION['type_user'] == 'admin') {
		echo '<tr>';
			echo '<td>Одобрить пользователя?:</td>';
			if ($row['moderation']) {
				echo '<td><input type="radio" name="moderation" value=1 CHECKED>Да';
			}
			else {
				echo '<td><input type="radio" name="moderation" value=1>Да';
			};
			if (!$row['moderation']) {
				echo '<input type="radio" name="moderation" value=0 CHECKED>Нет</td>';
			}
			else {
				echo '<input type="radio" name="moderation" value=0>Нет</td>';
			};
			echo '<td></td>';
		echo '</tr>';
		}
		?>
		<tr>
			<td>Аватарка:</td>
			<td><input type="file" name="avatarka" class="btn btn-default" value="<?php echo $row['avatarka']?>"></td>
			<td><a href="/index.php/page_edit?page_id=<?php echo $_GET['page_id']?>&edit=1&ft=<?php echo $row['avatarka']?>">Удалить фото</a></td>
		</tr>
		<tr>
		<tr>
			<td>Статус:</td>
			<td><input type="text" name="status" class="form-control" value="<?php echo $row['status']?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>Имя:</td>
			<td><input type="text" name="first_name" class="form-control" value="<?php echo $row['first_name']?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>Фамилия:</td>
			<td><input type="text" name="last_name" class="form-control" value="<?php echo $row['last_name']?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>Дата рождения:</td>
			<td><input type="text" name="birthday" class="form-control" value="<?php echo $row['birthday']?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>Город:</td>
			<td><input type="text" name="city" class="form-control" value="<?php echo $row['city']?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>О себе:</td>
			<td><textarea name="about_me" class="form-control" cols="30" rows="10"><?php echo $row['about_me']?></textarea></td>
			<td></td>
		</tr>
	</table>
	<input type="submit" name="page_edit" class="btn btn-primary" value="Сохранить изменения">
	</form>
	<a href="/index.php/page?page_id=<?php echo $_GET['page_id']?>">&lt&lt Назад к профилю</a>
</div>