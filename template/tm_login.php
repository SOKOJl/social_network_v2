<div class="fon_pechenka">
	<h1>Добро пожаловать на сайт Печеньки!</h1><br>
	<p>Для использрвания сайта Вам необходимо зайти под своим аккаунтом</p>
	<form action="/index.php" method="post">
		<fieldset>
			<legend>Вход</legend>
			<div class="form-group" id="form_search">
				<label for="login">Логин:</label>
				<input type="text" id="login" name="login" class="form-control" placeholder="Введите логин">
				<label for="password">Пароль:</label>
				<input type="password" id="password" name="password" class="form-control" placeholder="Введите пароль">
				<input type="submit" name="submit" class="btn btn-primary" value="Войти">
			</div>
		</fieldset>
	</form>
	<a href="/index.php/registration">Регистрация</a>
</div>