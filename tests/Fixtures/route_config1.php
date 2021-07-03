<?php

use PinkCrab\Route\Route_Group;

return array(
	( new Route_Group( '/base' ) )
		->set_namespace( 'pc/v1' ),
);
