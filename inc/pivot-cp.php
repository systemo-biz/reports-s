<?php
/*
Plugin Name: Pivot Table by CasePress
*/
/*
* [Description] Create pivot table from array
* @param
$array - исходный массив
$head - поле которое пойдет в шапку таблицы
$a - имя поля, значения которого пойдут в первую колонку (like A column in Excel)
$dim_func - функия измерения сводных данных. как правило количество, но может быть и сумма или среднее
$dim_field - поле по которому пойдет функция измерения, если это не count


@return $pivot_table - array with pivot table
*/
function cp_array_to_pivot_table($data, $a, $head, $dim_func = 'count', $dim_field = null) {

//Получаем массив по полю
$data_a = array();
foreach($data as $value) {
    $data_a[] = $value[$a];
}
$data_a = array_unique($data_a);
sort($data_a);

//Получаем массив значений (уникализированные и отсортированные) по полю для шапки
$data_head = array();
foreach($data as $value) {
    $data_head[] = $value[$head];
}
$data_head = array_unique($data_head);
sort($data_head);


//Определяем таблицу свода
$pivot_table = array();

//Заполняем первую строку таблицы - шапку
$thead = array();
//первая ячейка это заголовок первого столбца
$thead[] = $a;
foreach($data_head as $h_item){
     $thead[] = $h_item;
    }
//Добавляем шапку в таблицу
$pivot_table[] = $thead;


foreach($data_a as $a_item) {
     $row = array();

     //Указываем значение в первом столбце
     $row[] = $a_item;

     //Запускаем цикл, чтобы пройтись по значениям шапки
     foreach($data_head as $h_item){
          $izm = 0;
          foreach($data as $row_izm){
              if($row_izm[$a] == $a_item and $row_izm[$head] == $h_item) $izm++;
              //die('$row_izm[$a] = ' . $row_izm[$a] . ', ' . '$a_item = ' . $a_item . ', $row_izm[$head] = ' . $row_izm[$head] . ', $h_item = ' . $h_item);
          }
          //добавляем в ячейку сумму элементов пересечения
          $row[] = $izm;
     }

     $pivot_table[] = $row;
}

return $pivot_table;
}

/*
$src_array - исходный массив
$d_col - колонка для измерения
$d_method - метод измерения (count, sum ...)
$v_col - колонка для вертикали
$h_col - колонка для горизонтали
*/
function array_pivot_s($src_array, $d_col = 'dimension', $d_method = 'count', $h_col = 'horizontal', $v_col = 'vertical'){
  $pivot_array = array();

  $h_col_values = array_unique(array_column($src_array, $h_col));
  $v_col_values = array_unique(array_column($src_array, $v_col));

  //Если не указана колонка для вертикали
  if(empty($v_col_values)){

    //добавляем в первую строку заголовки колонок

    $pivot_array[] = $h_col_values;

    $row_array = array();
    foreach ($h_col_values as $col) {

      //Перебор всего массива и сложение значений
      $array_values = array();
      foreach ($src_array as $value) {
        if($value[$h_col] == $col) $array_values[] = $value[$d_col];
      }
      switch ($d_method) {
        case 'count':
          $dim_value = count($array_values);
          break;
        case 'sum':
          $dim_value = array_sum($array_values);
          break;
        case 'avg':
          $dim_value = array_sum($array_values)/count($array_values);
          break;
        default:
          $dim_value = 0;
          break;
      }


      $row_array[] = $dim_value;
    }

    $pivot_array[] = $row_array;
  } else {
    //Добавляем в первую строку название колонок, включая заголовок первой колонки
    $pivot_array[] = array_merge(array($v_col), $h_col_values);

    //Бежим по значения вертикали
    foreach ($v_col_values as $key => $value_v_col) {
      $row_array = array();

      //Бежим по значениям горизонтали
      foreach ($h_col_values as $col) {

        //Бежим по таблице и запоминаем данные
        $array_values = array();
        foreach ($src_array as $value) {
          //Если значение колонки и строки совпадает, то добавляем значение измеряемого параметра в массив вычисления значения
          if(($value[$h_col] == $col) and ($value[$v_col] == $value_v_col)) $array_values[] = $value[$d_col];
        }

        switch ($d_method) {
          case 'count':
            $dim_value = count($array_values);
            break;
          case 'sum':
            $dim_value = array_sum($array_values);
            break;
          case 'avg':
            $dim_value = array_sum($array_values)/count($array_values);
            break;
          default:
            $dim_value = 0;
            break;
        }

        //Помещаем в массив строки полученное значение измерения параметра
        $row_array[] = $dim_value;
      }

      //$row_array[] = $dim_value;

      //добавляем собранную строку в массив таблицы
      $pivot_array[] = array_merge(array($value_v_col), $row_array);
    }
  }



  return $pivot_array;
}
