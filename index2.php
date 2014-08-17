<?php

//import CommonConfig & CommonFunction
require_once('config.php');
require_once('functions.php');

//Connect DB
$dbh=connectDb();

//Create TaskArray
$tasks=array();

//Create SQLStatement
$sql="select * from tasks where type != 'deleted' order by seq";

foreach($dbh->query($sql) as $row){
	array_push($tasks,$row);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<title>Todo_App</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<style>
	.deleteTask,.dragTask,.editTask{
		cursor:pointer;
		color:blue;
	}
	.done{
		text-decoration: line-through;
		color:gray;
	}
	</style>
</head>
<body>
	
	<p>
	<div class="container">
		<label class="control-label" for="inputSuccess1">Add NewTask</label>
		<input type="text" id="title">
		<input type="button" class="btn btn-primary" id="addTask" value="追加">
	</div>
</p></p>
	<div class="container">
		<table class="table table-bordered" id="tasks">
			<tr>
				<td class="span1">□</td>
				<td class="span8">Task</td>
				<td class="span1">Edit</td>
				<td class="span1">Delete</td>
				<td class="span1">Drag</td>
			</tr>
			<?php foreach ($tasks as $task) : ?>
			<tr id="task_<?php echo h($task['id']); ?>" data-id="<?php echo h($task['id']); ?>">
				<td><input type="checkbox" class="checkTask" <?php if($task['type']=="done"){ echo "checked";} ?>></td>
				<td class="<?php echo h($task['type']); ?>"><?php echo h($task['title']); ?></td>
				<td <?php if ($task['type']=="notyet"){ echo 'class="editTask"'; } ?>><input type="button" class="btn btn-success btn-xs" value="Edit"></td>
				<td class="deleteTask"><input type="button" class="btn btn-danger btn-xs" value="Delete"></td>
				<td class="dragTask">[並替]</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div class="container" id="footer" style="background:grey">footer</div>
	<script>
	$(function(){
		//focus
		$('#title').focus();

		//addTask
		//セレクタ「#addTask」は動的に消えたりしないので、$(document)ではなく直接指定でOK
		$('#addTask').click(function(){
			var title=$('#title').val();
			//jquery.post(url,data,callback,(※type))
			//URL=_ajax_add_task.php
			//data=title:title
			//callback
			$.post('_ajax_add_task.php',{
				title:title
			},function(rs){
				var e=$(
					'<tr id="task_'+rs+'" data-id="'+rs+'">'+
					'<td><input type="checkbox" class="checkTask"></td>'+
					'<td></td>'+
					'<td class="editTask"><input type="button" class="btn btn-success btn-xs" value="Edit"></td>'+
					'<td class="deleteTask"><input type="button" class="btn btn-danger" value="Delete"></td>'+
					'<td class="dragTask">[並替]</td>'+
					'</tr>'
				);
				$('#tasks')
					.append(e)
					.find('tr:last td:eq(1)')
					.text(title);
				$('#title')
					.val('')
					.focus();
			});
		});
		//タスク並び替え
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

		//タスク編集
		//$(document).on('イベント','対象','処理内容')
		$(document).on('click','.editTask',function(){
			var id=$(this).parent().data('id');
			var title=$(this).prev().text();
			$('#task_'+id)
				.find('td:eq(1)')
				.empty()
				.append($('<input type="text">').attr('value',title))
				.change('<input type="button" value="更新" class="updateTask">')
			$('#task_'+id+' input:eq(1)').focus();
		});

		$(document).on('click','.updateTask',function(){
			//まず対象を指定するためにidが必要
			var id=$(this).parent().data('id');
			//更新後のテキストを取得。updateTaskボタンの前のテキストを取得
			var title=$(this).prev().val();
			//HTTP通信でページを読み込むメソッド,API
			//jquery.post(url,data,callback,type)
			//jquery.post('DB更新処理ファイルのURL','idとtitle','通信成功時のコールバック関数','typeは指定なしなので省略')
			//jquery.postは$.postで省略可能。
			$.post('_ajax_update_task.php',{
				id:id,
				title:title
			},function(rs){
				//編集完了後の表示項目を作成する。
				//編集後のテキストは直後のメソッドで追加するので、span要素だけ作成しておく。

				var e=$(
					'<td><input type="checkbox" class="checkTask"></td>'+
					'<td></td> '+
					'<td class="editTask"><input type="button" class="btn btn-success btn-xs" value="Edit"></td>'+
					'<td class="deleteTask"><input type="button" class="btn btn-danger" value="Delete"></td>'+
					'<td class="dragTask">[並替]</span>'
				);
				//タスクを一度空にして、作成した表示内容を追加。
				//その後、テキスト要素欄にあたるspan要素に新しいテキストを追加する。
				$('#task_'+id)
					.empty()
					.append(e)
					.find('td:eq(1)')
					.text(title);
			});
		});

		//タスクの完了済チェック
		$(document).on('change','.checkTask',function(){
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
		//タスクの削除
		$(document).on('click','.deleteTask',function(){
			if (confirm('本当に削除しますか？')){	
				//変数idにdata-idを挿入する
				var id=$(this).parent().data('id');
				$.post('_ajax_delete_task.php',{
					id:id
				},function(rs){
					$('#task_'+id).fadeOut(150);
				});
			}
		});
	});
	</script>
</body>
</html>