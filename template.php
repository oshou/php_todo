<!DOCTYPE html>
<html lang="ja">
<head>
	<title>Todo_App</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
	<link rel="stylesheet" href="css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<!--<script src="js/jquery.tbodyscroll.js"></script>-->
</head>
<body>
	<!--Header Start-->
	<!-- <div id="header" class="bg-primary">Header</div>-->
	<!--Header End-->

	<!--Contents Start-->
	<div id="wrapper">
		<!--Sidebar Start-->
		<div id="sidebar-wrapper">
			<ul class="sidebar-nav">
				<li class="sidebar-brand"><a href="index.php?p=all">Home</a></li>
				<li><a href="index.php?p=today">Today</a></li>
				<li><a href="index.php?p=week">Week</a></li>
				<li><a href="index.php?p=all">All</a></li>
				<li><a href="index.php?p=done">Done</a></li>
			</ul>
		</div>
		<!--Sidebar End-->

		<!--Main Start-->
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<br />
					<label class="control-label" for="inputSuccess1">Add NewTask</label>
					<input type="text" id="title" />
					<input type="date" id="plan" />
					<input type="button" class="btn btn-primary btn-xs addTask" value="追加">
					<br />
					<br />
					<table class="table table-condensed table-striped tbodyscroll" id="tasks">
						<thead>
							<tr>
								<th>Sort</th>
								<th>Check</th>
								<th>Title</th>
								<th>Date</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($tasks as $task) : ?>
							<tr id="task_<?php echo h($task['id']); ?>" data-id="<?php echo h($task['id']); ?>">
								<td class="span1 dragTask">[並替]</td>
								<td class="span1"><input type="checkbox" class="checkTask" <?php if($task['type']=="done"){ echo "checked";} ?>></td>
								<td class="span6 title <?php echo h($task['type']); ?>"><?php echo h($task['title']); ?></td>
								<td class="span3 plan <?php echo h($task['type']); ?>"><?php echo h($task['plan']); ?></td>
								<td class="span1 deleteTask"><input type="button" class="btn btn-danger btn-xs" value="Delete"></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!--Main End-->
	</div>
	<!--Contents End-->

	<script>
	$(function(){
		//Focus AddTaskInput
		$('#title').focus();

		//Add Task
		$(".addTask").click(function(){
			var title=$('#title').val();
			var plan=$('#plan').val();
			//jquery.post(url,data,callback)
			$.post('_ajax_add_task.php',{
				title:title,
				plan:plan
			},function(rs){
				console.log(rs);
				var e=$(
					'<tr id="task_'+rs+'" data-id="'+rs+'">'+
					'<td class="span1 dragTask">[並替]</td>'+
					'<td class="span1"><input type="checkbox" class="checkTask"></td>'+
					'<td class="span6 title"></td>'+
					'<td class="span2"></td>'+
					'<td class="span1 deleteTask"><input type="button" class="btn btn-danger btn-xs" value="Delete"></td>'+
					'</tr>'
				);
				$('#tasks')
					.append(e)
					.find('tr:last td:eq(2)')
					.text(title)
					.next()
					.text(plan);
				$('#title')
					.text('')
					.focus();
			});
		});

		//Delete Task
		$(document).on('click','.deleteTask',function(){
			if (confirm('本当に削除しますか？')){
				var id=$(this).parent().data('id');
				$.post('_ajax_delete_task.php',{
					id:id
				},function(rs){
					$('#task_'+id).fadeOut(150);
				});
			}
		});

		//Check Task
		$(document).on('click','.checkTask',function(){
			var id=$(this).parent().parent().data('id');
			$.post('_ajax_check_task.php',{
				id:id
			},function(rs){
				if($(this).hasClass('done')){
					$(this).removeClass('done').addClass('title').addClass('plan');
				} else{
					$(this).addClass('done').next().removeClass('title').removeClass('plan');;
				}
			});
		});

		//Edit taskTitle
		$('.title').on('click',function(){
			if(!$(this).hasClass('on')){
				$(this).addClass('on');
				var id=$(this).parent().data('id');
				var title=$(this).text();
				$(this).html('<input type="text" id="updateTask" value="'+title+'" />');
				$('.title > input').focus().on("change blur",function(){
					var inputTitle=$(this).val();
					if(inputTitle===''){
						inputTitle = this.defaultValue;
					};
					$(this).parent().removeClass('on').text(inputTitle);
					$.post('_ajax_update_title.php',{
						id:id,
						title:inputTitle
					},function(){
					});
				});
			};
		});

		//Edit taskPlan
		$('.plan').click(function(){
			if(!$(this).hasClass('on')){
				$(this).addClass('on');
				var id=$(this).parent().data('id');
				var plan=$(this).text();
				$(this).html('<input type="date" id="updateTask" value="'+plan+'" />');
				$('.plan > input').focus().on("change blur",function(){
					var inputPlan=$(this).val();
					if(inputPlan===''){
						inputPlan = this.defaultValue;
					};
					$(this).parent().removeClass('on').text(inputPlan);
					$.post('_ajax_update_plan.php',{
						id:id,
						plan:inputPlan
					},function(){
					});
				});
			};
		});

		//Sort Task
		$("#tasks tbody").sortable({
			axis:'y',
			opacity:0.2,
			handle:'.dragTask',
			update:function(){
				$.post('_ajax_sort_task.php',{
					task: $(this).sortable('serialize')
				});
			}
		});

		/*
		$(document).ready(function(){
			$('.tbodyscroll').tbodyscroll({
				thead_height:'30px',
				tbody_height:'70px',
			});
		});
		*/
	});
	</script>
</body>
</html>
