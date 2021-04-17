<div>
	<table>
		<tr>
			<td><img id="img_search" src="<?php echo $dir ?>" alt="<?php echo $row['first_name'] ?>" title="<?php echo $row['first_name'] ?>"></td>
			<td><a href="/index.php/page?page_id=<?php echo $row['user_id'] ?>"><?php echo $row['first_name'].' '.$row['last_name'] ?></a><?php echo $row['city'] ?><br>
			<?php set_friend_links($row['user_id']); ?>
			</td>
		</tr>
	</table>
</div>