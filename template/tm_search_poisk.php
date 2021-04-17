<div>
	<table>
		<tr>
		<?php
			echo '<td><img id="img_search" src="'.$dir.'" alt="'.$row["first_name"].'" title="'.$row["first_name"].'"></td>';
			echo '<td><a href="/index.php/page?page_id='.$row["user_id"].'">'.$row["first_name"].' '.$row["last_name"].'</a></td><td>'.$row["city"].'</td>';
		?>
		</tr>
	</table>
</div>