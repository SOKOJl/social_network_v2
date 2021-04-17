<div class="main_page_communication">
	<div class="dialogs">
		<?php list_dialogs() ?>
	</div>
	<div class="communication" id="block">
		<?php echo_dialog() ?> 
	</div>
	<?php
		if ($dialog=$_GET['dialog']) {
	?>
	<div class="form-group write_message">
		<a href="/index.php/communication?dialog=<?php echo $_GET['dialog'] ?>&delet_msg=1" title="1">Удалить переписку с пользователем</a>
		<form action="/index.php/communication?dialog=<?php echo $_GET['dialog'] ?>" method="post">
			<label for="write_message_text">Введите Ваше сообщение сюда</label>
			<input type="text" name="write_message_text" id="write_message_text" class="form-control" placeholder="Введите Ваше сообщение сюда">
			<input type="submit" name="send_message" class="btn btn-primary" value="Отправить">
		</form>
	</div>
	<?php
		}
	?>
</div>
<script type="text/javascript">
  var block = document.getElementById("block");
  block.scrollTop = block.scrollHeight;
</script>