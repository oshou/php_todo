<?php

//共通設定ファイル、共通関数ファイルを読み込み
require_once('config.php');
require_once('functions.php');

//DBに接続
$dbh=connectDb();

//タスク一覧は配列で表現する
$tasks=array();
//未削除のタスクを表示するSQL文を作成
$sql="select * from tasks where type != 'deleted' order by seq";

foreach($dbh->query($sql) as $row){
	array_push($tasks,$row);
}

//var_dump($tasks);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>Todo_App</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
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
	<h1>Todo_App</h1>
	<ul id="tasks">
		<?php foreach ($tasks as $task) : ?>
		<li id="task_<?php echo h($task['id']); ?>" data-id="<?php echo h($task['id']); ?>">
			<input type="checkbox" class="checkTask" <?php if($task['type']=="done"){ echo "checked";} ?>>
			<span class="<?php echo h($task['type']); ?>"><?php echo h($task['title']); ?></span>
			<span <?php if ($task['type']=="notyet"){ echo 'class="editTask"'; } ?>>[編集]</span>
			<span class="deleteTask">[削除]</span>
			<span class="dragTask">[並替]</span>
		</li>
		<?php endforeach; ?>
	</ul>
	<script>
	$(function(){
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
				.empty()
				.append($('<input type="text">').attr('value',title))
				.append('<input type="button" value="更新" class="updataTask"')
			$('#task_'+id+' input:eq(0)').focus();
		});

		$(document).on('click','.updataTask',function(){
			//まず対象を指定するためにidが必要
			var id=$(this).parent().data('id');
			//更新後のテキストを取得。updataTaskボタンの前のテキストを取得
			var title=$(this).prev().val();
			//
			$.post('_ajax_updata_task.php',{

			})
		})
		/*
		$(document).on('click','.editTask',function(){
			var id=$(this).parent().data('id');
			var title=$(this).prev().text();
			$('#task_'+id)
			.empty()
			.append($('<input type="text">').attr('value',title))
			.append('<input type="button" value="更新" class="updateTask">');
			$('#task_'+id+' input:eq(0)').focus();
		});*/
		/*
		$(document).on('click','.updateTask',function(){
			var id=$(this).parent().data('id');
			var title=$(this).prev().val();
			$.post('_ajax_update_task.php',{
				id:id,
				title:title
			},function(rs){
				var e=$(
					'<input type="checkbox" class="checkTask">'+
					'<span></span>'+
					'<span class="editTask">[編集]</span>'+
					'<span class="deleteTask">[削除]</span>'+
					'<span class="dragTask">[並替]</span>'+
				);
				$('#task_'+id).empty().append(e).find('span:eq(0)').text(title);
			});
		});
*/
		//タスクの完了済チェック
		$(document).on('click','.checkTask',function(){
			var id=$(this).parent().data('id');
			var title=$(this).next();
			$.post('_ajax_check_task.php',{
				id:id
			},function(rs){
				if(title.hasClass('done')){
					title.removeClass('done')
				} else{
					title.addClass('done');
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
