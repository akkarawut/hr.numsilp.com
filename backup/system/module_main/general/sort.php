<?php
class Sort
{
    /**
    | Group | Sort
    | Description | swap
    | $table_name | STRING table name
    | $sort_order_id_1 | INT ID target 1
    | $sort_order_id_2 | INT ID target 2
     */
    public static function swap($table_name, $sort_order_id_1, $sort_order_id_2)
    {
        $id1 = DB::get($table_name, 'id', ['sort_order_id' => $sort_order_id_1]);
        $id2 = DB::get($table_name, 'id', ['sort_order_id' => $sort_order_id_2]);

        if (!self::checkMinMaxId($table_name, [$sort_order_id_1, $sort_order_id_2])) {
            return false;
        }
        if ($sort_order_id_1 === $sort_order_id_2) {
            return false;
        }
        if ($id1 === null || $id2 === null) {
            return false;
        }

        $data =  ['sort_order_id' => $sort_order_id_1];
        $where = ['id' => $id2];
        $result = DB::update($table_name, $data, $where);

        //check result is true or false
        if ($result === false) {
            return $result;
        }

        $data =  ['sort_order_id' => $sort_order_id_2];
        $where = ['id' => $id1];
        $result = DB::update($table_name, $data, $where);

        //check result is true or false
        if ($result === false) {
            $data =  ['sort_order_id' => $sort_order_id_2];
            $where = ['id' => $id2];
            $result = DB::update($table_name, $data, $where);
            return false;
        }

        return $result;
    }

    /**
    | Group | Sort
    | Description | moveToTop
    | $table_name | STRING table name
    | $sort_id_target | INT Sort ID target
     */
    public static function moveToTop($table_name, $sort_id_target)
    {
        $min = Amst::min($table_name, 'sort_order_id');
        if ($sort_id_target <= $min) {
            return false;
        }

        return self::moveRows($table_name, $sort_id_target, $min);
    }

    /**
    | Group | Sort
    | Description | moveToBottom
    | $table_name | STRING table name
    | $sort_id_target | INT ID target
     */
    public static function moveToBottom($table_name, $sort_id_target)
    {
        $max = Amst::max($table_name, 'sort_order_id');
        if ($sort_id_target >= $max) {
            return false;
        }

        return self::moveRows($table_name, $sort_id_target, $max);
    }

    /**
    | Group | Sort
    | Description | moveToPosition
    | $table_name | STRING table name
    | $sort_id_target | INT ID target
    | $sort_id_moveto | INT ID moveto
    | $position | STRING above or below
     */
    public static function moveToPosition($table_name, $sort_id_target, $sort_id_moveto, $position)
    {
        $min = Amst::min($table_name, 'sort_order_id');

        if ($position === 'below') {
            if ($sort_id_moveto < $sort_id_target) {
                $sort_id_moveto++;
            }
        } else if ($position === 'above') {
            $sort_id_moveto--;
            if ($sort_id_moveto < $min) {
                $sort_id_moveto = $min;
            }
        } else {
            return false;
        }
        if ($sort_id_target == $sort_id_moveto) {
            return false;
        }
        if (!self::checkMinMaxId($table_name, [$sort_id_target, $sort_id_moveto])) {
            return false;
        }

        return self::moveRows($table_name, $sort_id_target, $sort_id_moveto);
    }

    /**
     * Check sort_order_id is in range of min and max value
     *
     * @author BALL
     * @param STRING $table_name Table Name
     * @param ARRAY $sort_order_id
     * @return BOOLEAN
     */
    private static function checkMinMaxId($table_name, $sort_order_id)
    {
        $max = Amst::max($table_name, 'sort_order_id');
        $min = Amst::min($table_name, 'sort_order_id');
        foreach ($sort_order_id as $sort_id) {
            if ($max < $sort_id || $min > $sort_id) {
                return false;
            }
        }

        return true;
    }

    /**
     * Change sort_order_id by target and limit
     *
     * @author BALL
     * @param STRING $table_name Table Name
     * @param INT $sort_order_target
     * @param INT $limit
     * @return BOOLEAN
     */
    private static function moveRows($table_name, $sort_id_target, $target_moveto)
    {
        $result = false;
        if (!self::checkMinMaxId($table_name, [$sort_id_target])) {
            return false;
        }

        $id1 = DB::get($table_name, 'id', ['sort_order_id' => $sort_id_target]);

        if ($sort_id_target > $target_moveto) {
            $operater1 = '+';
            $operater2 = '<';
            $operater3 = '>=';
            $operater4 = '-';
        } else {
            $operater1 = '-';
            $operater2 = '>';
            $operater3 = '<=';
            $operater4 = '+';
        }

        $data =  ['sort_order_id[' . $operater1 . ']' => 1];
        $where = ['sort_order_id[' . $operater2 . ']' => $sort_id_target, 'sort_order_id[' . $operater3 . ']' => $target_moveto];
        $result = DB::update($table_name, $data, $where);

        //check result is true or false
        if ($result === false) {
            return $result;
        }

        $data =  ['sort_order_id' => $target_moveto];
        $where = ['id' => $id1];
        $result = DB::update($table_name, $data, $where);

        //check result is true or false
        if ($result === false) {
            $data =  ['sort_order_id[' . $operater4 . ']' => 1];
            $where = ['sort_order_id[' . $operater2 . ']' => $sort_id_target, 'sort_order_id[' . $operater3 . ']' => $target_moveto];
            $result = DB::update($table_name, $data, $where);
            return false;
        }

        return true;
    }
}
