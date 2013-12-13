<div class="<?php echo self::PREFIX; ?>message <?php echo $class;?>">
   
	<?php foreach( $this->notices[ $type ] as $messageData ) : ?>
		<?php if( $messageData[ 'mode' ] == 'user' || $this->debugMode ) : ?>
			<p><?php echo $messageData[ 'message' ]; ?></p>
		<?php endif; ?>
	<?php endforeach; ?>
	
</div>