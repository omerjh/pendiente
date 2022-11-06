<aside class="main-sidebar elevation-4 sidebar-dark-primary">
	<!-- Brand Logo -->
	<a href="visInicio.php" class="brand-link">
		<img src="imagenes/icono.png" alt="Sogem Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> 
		<span class="brand-text font-weight-light">Sogem</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="imagenes/fotos/<?php print $_SESSION["sogem_u_imagen_perfil"];?>" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block"><?php print $_SESSION["sogem_u_nombre"];?></a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
				 with font-awesome or any other icon font library -->
				<?php 
					$loTabla->fpMenu($_SESSION["sogem_u_permisos"], $lsArchivo, $pbInicio);
				?>
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>