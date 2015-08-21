# reports-s
Reports (KPI, indicators, tables, statistics) for WordPress


# Функция кросстабуляции и свода таблицы (pivot table) для php

- `array_pivot_s($src_array, $d_col = 'dimension', $d_method = 'count', $h_col = 'horizontal', $v_col = 'vertical')` - функция свод данных (кросс табуляции таблицы, pivot table).

`$src_array` - исходный массив, который передается для свода
`$d_col` - ключ массива, значения которого должны попасть под измерение
`$d_method` - метод измерения (count, sum, avg)
`$h_col` - ключ массива, значения которого попадут в горизонтальную колонку таблицы
`$v_col` - ключ массива, значения которого попадут в вертикальную колонку таблицы

- `cp_array_to_pivot_table()` - функция добавлена на удаление. В новых версиях ее не будет.
