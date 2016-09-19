<?php 
$type = new Type();
$types = $type->selectAll();
?>
<nav class="navbar navbar-primary" role="navigation">
	<div class="container-fluid">
		<div class="navbar-collapse">
			<ul class="nav navbar-nav">
				<li>
					<a href="index.php"><img src="img/home.png" width="20" /></a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Sequence Analysis<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<?php 
							for ($i = 0; $i < count($types); $i++) {
								echo "<li>";
								echo "<a href='#'><strong>".$types[$i]->getName()."</strong></a>";
								echo "</li>";
								$microorganism = new Microorganism("", $types[$i]->getId());
								$microorganisms = $microorganism -> selectByType();
								for ($j = 0; $j < count($microorganisms); $j++) {
									echo "<li>";
									echo "<a href='index.php?pid=".base64_encode("ui/viewGenes.php")."&idMicroorganism=".$microorganisms[$j]->getId()."'>&emsp;".$microorganisms[$j]->getName()."</a>";
									echo "</li>";										
								}
								
							}
						?>
					</ul>
				</li>
				<li>
					<a href="<?php echo "index.php?pid=".base64_encode("ui/about.php") ?>">About PSSA</a>
				</li>
			</ul>
		</div>
	</div>
</nav>