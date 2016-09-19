<?php
$graphFile=$_GET["graphFile"];
?>
<style>
.node {
  stroke: #fff;
  stroke-width: 1.5px;
}

.link {
  stroke: #999;
  stroke-opacity: .6;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-<?php echo $color?>">
				<div class="panel-heading">
					<h3 class="panel-title">Force-Direct Graph</h3>
				</div>
				<div class="panel-body">
				The Force-Directed Graph allows to visualize the distribution of secuences regarding the reference sequence (e.g., Con1-1b), which is represented by the central blue node.<br>
				Each patient corresponds to the collection of nodes with the same color. However, some nodes from the same patient might have different groups. Each group of each patient indicates that the sequences represented by the gruped nodes contain the same amount of mutations (Amino Acid changes).<br>
				By placing the mouse pointer over a node, the following information is shown: 1) patient file name, 2) sequence name, 3) amount of mutations, 4) positions and mutations regarding the reference sequence.
				<div id="content"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
var width = $("#content").width(),
    height = 500;

var color = d3.scale.category20();

var force = d3.layout.force()
    .charge(-120)
    .linkDistance(30)
    .size([width, height]);

var svg = d3.select("#content").append("svg")
    .attr("width", width)
    .attr("height", height);

d3.json("<?php echo "graphFiles/".$graphFile; ?>", function(error, graph) {
  if (error) throw error;

  force
      .nodes(graph.nodes)
      .links(graph.links)
      .start();

  var link = svg.selectAll(".link")
      .data(graph.links)
    .enter().append("line")
      .attr("class", "link")
      .style("stroke-width", function(d) { return Math.sqrt(d.value); });

  var node = svg.selectAll(".node")
      .data(graph.nodes)
    .enter().append("circle")
      .attr("class", "node")
      .attr("r", 5)
      .style("fill", function(d) { return color(d.group); })
      .call(force.drag);

  node.append("title")
      .text(function(d) { return d.name; });

  force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
  });
});
</script>				
