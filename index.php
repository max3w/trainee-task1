<!DOCTYPE html>
<html>
<body>

<h1>Trainee-task1</h1>

<?php
    #Кодировка страницы для браузера и краткая информация
	header("Content-Type: text/html; charset=utf-8");
    #Получаем файл с сервера и считывать его функцией simplexml_load_file().

	$url_file = simplexml_load_file("http://trainee.abaddon.pp.ua/catalog.xml"); //Оригинальный файл
    $new_file = simplexml_load_file("catalog.xml"); //Отредактированый
    //$url_file = simplexml_load_file("catalog.xml"); //раскоментируем для теста обновлений

?>

<?php
    
    echo ('<p>Чтение оригинального файла: <a href="http://trainee.abaddon.pp.ua/catalog.xml" target="_blanc">http://trainee.abaddon.pp.ua/catalog.xml</a></p>');
        //Небольшая проверка первой переменной
        if ($url_file) {echo ('<p class="green">Ок!</p>');
        } 
        else {echo ('Проверьте правильность ссылки');}

    echo ('<p>Измененный файл (для теста обновлений) <a href="/catalog.xml" target="_blanc">catalog.xml</a></p>');
   
        //Небольшая проверка второй переменной
        if ($new_file) {echo ('<p class="green">Ок!</p>');}
        else {echo ('Проверьте правильность ссылки');}

    echo ('<pre><p>Этот блок вызывает вопросы...</p>');
    //Сравнение двух версий
    if ($url_file == $new_file) {
        echo ('<p>Не изменились</p>');
    } else {
        echo ('<p>Изменились <i>- if ($url_file == $new_file) {} Такая проверка работает</i></p>');
    }

            //Проверка размера переменной (но наверное есть лучший способ...)
            $before=0;
            $a=$url_file; //тестируемая переменная
            $b=$new_file; //измененный файл
            $before = memory_get_usage();
            unset($a);
            echo 'Размер первой переменной составил: ',$before-$a,' байт<br>';
            unset($b);
            echo 'Размер второй переменной составил: ',$before-$b,' байт <i>- тут не понял почему одинаковы</i>?';
    echo ('</pre>');

     #- Первая таблица
    /*содержит информаци о Клиенте. Состоит из трёх колонок.
    В первой колонке названия полей, 
    во второй - данные из XML, 
    в третьей должно обтображаться обновлялись данные с прошлой загрузки или нет ("Обновлено"/"Не обновлено").
    Следующие данные должны быть в таблице: 
    + ФИО 
    + номер мобильного 
    + TIN (идентификационный код)*/
    echo ('<h2>Первая таблица информация о Клиенте</h2>');
    echo ('<pre>');
    print_r($url_file->personinfo->asXML());
    echo ('</pre>');

    echo ('<table border="1" style="border-collapse: collapse; width: 100%;">
    <tbody>
    <tr>
    <td style="width: 33.3333%;">Названия полей</td>
    <td style="width: 33.3333%;">Данные из XML</td>
    <td style="width: 33.3333%;">Обновлялись ли данные</td>
    </tr>
    <tr>
    <td style="width: 33.3333%;">
    <ul>
         <li>ФИО:</li>
         <li>Номер мобильного:</li>
         <li>c (идентификационный код):</li>
    </ul>
    </td>
    <td style="width: 33.3333%;">'); ?>

    <?php //Получаем переменные для сохранения
        $surname = $url_file->personinfo->surname->asXML(); //a
        $first_name = $url_file->personinfo->first_name->asXML(); //b
        $phone = $url_file->personinfo->mobile_phone->asXML(); //c
        $tin = $url_file->personinfo->tin->asXML(); //d
    ?>
        <!--Запись первой таблицы в localStorage-->
        <!--Каждый домен может хранить до 5 МБ данных в LocalStorage.
        Кроме того, наши данные не отправляются на сервер при выполнении HTTP-запроса.
        Данные в LocalStorage не имеют срока годности. 
        Его можно удалить с помощью JavaScript или очистив кеш браузера.-->

        <script>
            localStorage.setItem('surname', '<?php echo $surname; ?>');  
            localStorage.setItem('first_name', '<?php echo $first_name; ?>');
            localStorage.setItem('phone', '<?php echo $phone; ?>');  
            localStorage.setItem('tin', '<?php echo $tin; ?>'); 
       </script>

        <?php //Получаем с lacalestorage

            $a = "<script>document.write(localStorage.getItem('surname'));</script>";
            $b = "<script>document.write(localStorage.getItem('first_name'));</script>";
            $c = "<script>document.write(localStorage.getItem('phone'));</script>";
            $d = "<script>document.write(localStorage.getItem('tin'));</script>";
            //echo ($a . $b . $c . $d);
       ?> 

    <?php echo ('<ul>
         <li>'. $surname . '</li>
         <li>'. $first_name .' </li>
         <li>'. $phone .'</li>
         <li>'. $tin .'</li>
        </ul>
    </td>
    <td style="width: 33.3333%;">'); ?>

    <?php echo ('Видим что переменные разной длинны:<br>');
    var_dump($a);
    echo ('|');
    var_dump($surname); 
    ?>

    <?php echo ('<ul>');?> 

    <?php 
    
    if ($a == $surname ) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
    if ($b == $first_name ) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
    if ($c == $phone ) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
    if ($d == $tin ) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
    
    ?>
    
    <?php echo ('</ul>'); ?>

    <?php echo ('Пробуем другой метод через $_SESSION:<br>'); ?>

    <?php 

    session_start(); //Инициализируем сессию только один раз за скрипт
    //unset($_SESSION['t1']); //Для очистки
    if (!isset($_SESSION['t1'])) { //Если сессия таблицы 1 не определена тогда запишем значения      
    
    $_SESSION['t1'] = array($surname,$first_name,$phone,$tin);
    //Назначаем чтобы при первом запуске сравнивалка работала
    $a = $_SESSION['t1'][0];
    $b = $_SESSION['t1'][1];
    $c = $_SESSION['t1'][2];
    $d = $_SESSION['t1'][3];

    echo ('Сессия t1 не существует! Записали в нее данные');

    } else { //Если опредена тогда назначим текущее значение xml файла
    $a = $_SESSION['t1'][0];
    $b = $_SESSION['t1'][1];
    $c = $_SESSION['t1'][2];
    $d = $_SESSION['t1'][3];
    } 

    echo ('<ul>');
    if ($a == $surname ) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
    if ($b == $first_name ) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
    if ($c == $phone ) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
    if ($d == $tin ) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
    echo ('</ul>');

    echo ('Предыдущие данные: ');
    print_r($_SESSION['t1']);

    echo ('<hr>Новые данные: ');
    $news = array($surname, $first_name,$phone, $tin);
    print_r($news);

    ?>
    
    <?php echo ('</td>
    </tr>
    </tbody>
    </table>');

    
    # Информация о заказе
    echo ('<h2>Вторая таблица содержит информацию о заказе</h2>');
    echo ('<pre>');
    print_r($url_file->goods->good);
    echo ('</pre>');    

    echo ('<table border="1" style="border-collapse: collapse; width: 100%;">
    <tbody>
    <tr>
    <td style="width: 25%;">Названия полей</td>
    <td style="width: 25%;">Номер заказа(orderId)+ID товара</td>
    <td style="width: 25%;">Данные из XML</td>
    <td style="width: 25%;">Обновлялись ли данные</td>
    </tr>
    <tr>
    <td style="width: 25%;">
    <ul>
         <li>Номер заказа(orderId):</li>
         <li>ID товара:</li>
    </ul>
    </td>
    <td style="width: 25%;">');

    //Разбор массива заказов
    foreach($url_file->goods->good as $gd) {
    echo ('<ul>
        <li>OrderId: '.$gd->id->asXML() . '</li>
        <li>ID: '. $gd->classificationid->asXML() .'</li>
   </ul>');
   }
   
   echo ('</td><td style="width: 25%;">');

    foreach($url_file->goods->good as $gd) {

        echo ('<ul>
            <li>Название: '.$gd->name->asXML() . '</li>
            <li>Цена: '. $gd->price->asXML() .'</li>
            <li>Количество: '.$gd->amount->asXML() . '</li>
       </ul>');
       }

    echo ('</td>
    <td style="width: 25%;">');
    
    //unset($_SESSION); //Для очистки
    $i=0;

    foreach($url_file->goods->good as $gd) {
        
        $i++;
        $i="s".$i;
        $_SESSION[$i] = array($gd->name->asXML(),$gd->price->asXML(),$gd->amount->asXML()); //пришлось добавить так как постоянно пишет сессия не определена. Не пойму почему...
        
        if (!isset($_SESSION[$i])) { //Если сессия таблицы 2 не определена тогда присвоим значения      
            
            $_SESSION[$i] = array($gd->name->asXML(),$gd->price->asXML(),$gd->amount->asXML());
            
            //Назначаем чтобы при первом запуске сравнивалка работала       
            $e = $_SESSION[$i][0];
            $f = $_SESSION[$i][1];
            $g = $_SESSION[$i][2];

            echo ('Сессия ' . $i . ' не существует! Записали в нее данные');

            } else { //Если опредена тогда назначим текущее значение xml файла

            $e = $_SESSION[$i][0];
            $f = $_SESSION[$i][1];
            $g = $_SESSION[$i][2];

            echo ('Сессия ' . $i);
            }

        echo ('<ul>');
        if ($e == $gd->name->asXML()) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
        if ($f == $gd->price->asXML()) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
        if ($g == $gd->amount->asXML()) {echo ('<li>Не обновлено</li>');} else {echo ('<li>Обновлено</li>');}
        echo ('</ul>');

       }
       $i=0;
    foreach($url_file->goods->good as $gd) { //Проверочный цикл

    $i++;
    echo ('<hr>Предыдущие данные: ');
    print_r($_SESSION[$i]);

    echo ('<br>Новые данные: ');
    $newd = array($gd->name->asXML(), $gd->price->asXML(),$gd->amount->asXML());
    print_r($newd);
    echo ('<hr>');
       }

    echo ('</td>
    </tr>
    </tbody>
    </table>');

    echo ('End');

?>

<?php

#Визуальное сравнение файлов

echo ('
<table style="border-collapse: collapse; width: 100%;">
<tbody>
<tr>
<td style="width: 50%;">');
 
    echo ('<h2>Print_r оригинального файла</h2>');
    echo ('<pre>');
    print_r(simplexml_load_file("http://trainee.abaddon.pp.ua/catalog.xml"));
    echo ('</pre>');
 
    echo ('</td><td style="width: 50%;">');

    echo ('<h2>Print_r измененного файла</h2>');
    echo ('<pre>');
    print_r($new_file);
    echo ('</pre>');

echo ('</td>
</tr>
</tbody>
</table>');
?>

<style>
    .green {color:green;}
    pre {
    background-color: #eee;
    padding: 10px 30px;
}
</style>

</body>
</html>
