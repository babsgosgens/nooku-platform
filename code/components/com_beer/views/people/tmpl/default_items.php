<? defined('_JEXEC') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$people as $person) : ?>
	<tr class="<?php if ($person->odd) { echo 'even'; } else { echo 'odd'; } ?>">
		<td>
			<?= $i + 1; ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=person&id='.$person->id) ?>" />
				<?= @$escape($person->name); ?>
			</a>
		</td>
		<td>
			<?= @$escape($person->position); ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=office&id='.$person->office) ?>" />
				<?= @$escape($person->office_name); ?>
			</a>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=department&id='.$person->department) ?>" />
				<?= @$escape($person->department_name); ?>
			</a>
		</td>
	</tr>
	<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>