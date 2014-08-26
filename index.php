<?php
//import CommonConfig & CommonFunction
require_once('config.php');
require_once('functions.php');
require_once('cconsole.php');

//Connect DB
$dbh=connectDb();

//Create TaskArray
$tasks=array();

//Create SQLStatement
$sql="select * from tasks where type != 'deleted' order by plan";

foreach($dbh->query($sql) as $row){
	array_push($tasks,$row);
}
?>

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
	<script type="text/javascript" src="js/footerFixed.js"></script>
</head>
<body>			
	<!--Header Start-->
	<div class="container">
		<div id="header" class="bg-primary">Header</div>
	</div>
	<!--Header End-->

	<!--Contents End-->		
	<div class="container">	
		<div id="contents">
			<br />
			<label class="control-label" for="inputSuccess1">Add NewTask</label>
			<input type="text" id="title">
			<input type="date" id="plan" />
			<input type="button" class="btn btn-primary btn-xs addTask" value="追加">
			<br />
			<br />
			<table class="table table-condensed table-striped" id="tasks">
				<thead>
					<th>Check</th>
					<th>Title</th>
					<th>Date</th>
					<th>Delete</th>
					<th>Drag</th>
				</thead>
				<tbody>
				<?php foreach ($tasks as $task) : ?>
					<tr id="task_<?php echo h($task['id']); ?>" data-id="<?php echo h($task['id']); ?>">
						<td class="col-sm-1"><input type="checkbox" class="checkTask" <?php if($task['type']=="done"){ echo "checked";} ?>></td>
						<td class="col-sm-6 editTitle <?php echo h($task['type']); ?>"><?php echo h($task['title']); ?></td>
						<td class="col-sm-3 editPlan <?php echo h($task['type']); ?>"><?php echo h($task['plan']); ?></td>
						<td class="col-sm-1 deleteTask"><input type="button" class="btn btn-danger btn-xs" value="Delete"></td>
						<td class="col-sm-1 dragTask">[並替]</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<!--Contents End-->		

	<!--footer Start-->		
	<div class="container">
		<div id="footer" class="bg-primary">footer</div>
	</div>
	<!--Contents End-->		

	<script>
	$(function(){
		//focus
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
				var e=$(
					'<tr id="task_'+rs+'" data-id="'+rs+'">'+
					'<td class="col-sm-1"><input type="checkbox" class="checkTask"></td>'+
					'<td class="col-sm-6 editTitle"></td>'+
					'<td class="col-sm-2"></td>'+
					'<td class="col-sm-1 deleteTask"><input type="button" class="btn btn-danger btn-xs" value="Delete"></td>'+
					'<td class="col-sm-1 dragTask">[並替]</td>'+
					'</tr>'
				);
				$('#tasks')
					.append(e)
					.find('tr:last td:eq(1)')
					.text(title)
					.next()
					.text(plan);
				$('#title')
					.val('')
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
		//Sort Task
		/*
		$("#tasks").sortable({
			axis:'y',
			opacity:0.2,
			handle:'.dragTask',
			update:function(){
				$.post('_ajax_sort_task.php',{
					task:$(this).sortable('serialize')
				});
			}
		});
		*/

		//Edit taskTitle
		$('.editTitle').click(function(){
			if(!$(this).hasClass('on')){				
				$(this).addClass('on');
				var id=$(this).parent().data('id');
				var title=$(this).text();
				$(this).html('<input type="text" id="updateTask" value="'+title+'" />');
				$('.editTitle > input').focus().blur(function(){
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
		$('.editPlan').click(function(){
			if(!$(this).hasClass('on')){				
				$(this).addClass('on');
				var id=$(this).parent().data('id');
				var plan=$(this).text();
				$(this).html('<input type="date" id="updateTask" value="'+plan+'" />');
				$('.editPlan > input').focus().blur(function(){
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

		//タスクの完了済チェック
		/*
		$(document).on('click','.checkTask',function(){
			var id=$(this).parent().data('id');
			var title=$(this).next();
			$.post('_ajax_check_task.php',{
				id:id
			},function(rs){
				if(title.hasClass('done')){
					title.removeClass('done').addClass('editTask');
				} else{
					title.addClass('done').next().removeClass('editTask');
				}
			});
		});
		*/
		//タスクの削除
	});
	</script>
</body>
</html>