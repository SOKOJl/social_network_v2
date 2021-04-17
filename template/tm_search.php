<div class="form_search" >
<form action="/index.php/search" method="get" role="form">
	<fieldset>
		<legend>Поиск по:</legend>
		<div class="form-group" id="form_search">
			<label for="search_first_name">Имя:</label>
			<input type="text" id="search_first_name" name="search_first_name" class="form-control" placeholder="Введите Имя">
			<label for="search_last_name">Фамилия:</label>
			<input type="text" id="search_last_name" name="search_last_name" class="form-control" placeholder="Введите Фамилию">
			<label for="search_year_old">Возраст:</label>
			<input type="text" id="search_city" name="search_year_old" class="form-control" placeholder="Введите возраст">
			<label for="search_city">Город:</label>
			<input type="text" id="search_city" name="search_city" class="form-control" placeholder="Введите город">
			<input type="submit" name="search" value="Найти" class="btn btn-primary">
				<?php
					if ($_SESSION['type_user'] == 'admin') {
						echo '<input type="submit" name="new_people" value="Ожидают модерации" class="btn btn-primary">';
					}
				?>
		</div>
	</fieldset>
</form>
</div>
<?php echo $content ?>