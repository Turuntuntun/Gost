<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Гостевая книга</h1>
			<div>
				<nav>
				  <ul class="pagination">
					<li class="disabled">
						<a href="?page=1"  aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
<?php
    $host = 'localhost';
    $user = 'root';
    $password = 'nfnmzyf40404';
    $db_name = 'Gost_book';
   //Соединяемся с базой данных используя наши доступы:
    //Устанавливаем кодировку (не обязательно, но поможет избежать проблем):
    $link = mysqli_connect($host, $user, $password, $db_name);
    mysqli_query($link, "SET NAMES 'utf8'");
    
    if(isset($_POST['submit'])){
    	$name = $_POST['name'];
    	$text = $_POST['text'];
    	$time = time();
    	$date = date('Y-m-d H:i:s',$time); 
    	$query1 = "INSERT INTO gost SET `name` = '$name' , `date` = '$date',   `text` = '$text'";    
    	mysqli_query($link, $query1) or die(mysqli_error($link));	
    }

    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }else{
        $page = 1;
    }
    $notesOnPage = 3;
    $from = ($page - 1) * $notesOnPage;
    
    $query = 'SELECT COUNT(*) as count FROM gost';
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $count = mysqli_fetch_assoc($result)['count'];
    $pagescount = ceil($count/$notesOnPage);
	for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
    for ($i = 1; $i <= $pagescount; $i++){
        if($page == $i){
             echo '<li class="active"><a href = "?page='.$i.'">'.$i.'</a></li> ';
        }else{
             echo '<li><a href = "?page='.$i.'">'.$i.'</a></li> ';
    }
    }

    $query = "SELECT * FROM gost ORDER BY date DESC LIMIT $from, $notesOnPage";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
?>
                    <li>
						<a href="?page=<?php echo $pagescount?>" aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				 </ul>
			</nav>
		</div>
<?php
    foreach ($data as $key => $value) {
        $res = '';
        $res .= '<div class = "note"><p><span class="date">';
        $res .= $value['date'];
        $res .= '</span><span class="name"> ';
        $res .= $value['name'];
        $res .= '</span></p><p>';
        $res .= $value['text'];
        $res .= '</p></div>';
        echo $res;
    }
    if(isset($_POST['submit'])){
       $res1 = '<div class="info alert alert-info"> Запись успешно сохранена! </div>';
       echo $res1;
    }

?>
       
           
			<div id="form">
				<form action="#form" method="POST">
					<p><input class="form-control" placeholder="Ваше имя" name = 'name'></p>
					<p><textarea class="form-control" placeholder="Ваш отзыв" name = 'text'></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить" name = 'submit'></p>
				</form>
			</div>
		</div>
	</body>
</html>