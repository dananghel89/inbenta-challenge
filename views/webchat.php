<?php
/**
 * Chatbot view.
 */

require get_part('include/header');
?>

<div id="webchat" class="box">
	<table>
		<tr class="header">
			<td>
				<div class="row nav">
					<div class="left">
						<h1><?= CoreConfig::$app->name ?></h1>
					</div>

					<div class="right">
						<?php if ( !empty($header) ) : ?>
							<small><?= $header ?></small>
						<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>

		<tr is="message-row"></tr>
	</table>
</div>

<?php
require get_part('include/footer');
