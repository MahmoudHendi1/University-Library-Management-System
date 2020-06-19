<?php
include 'inc\DB.php';
$db = new DB();
$tblName = 'book';
$conditions = array();
if(!empty($_POST['type']) && !empty($_POST['val'])){
    if($_POST['type'] == 'search'){
        $conditions['search'] = array('name'=>$_POST['val'],'author'=>$_POST['val']);
        $conditions['order_by'] = 'id DESC';
    }elseif($_POST['type'] == 'sort'){
        $sortVal = $_POST['val'];
        $sortArr = array(
            'new' => array(
                'order_by' => 'id DESC'
            ),
            'asc'=>array(
                'order_by'=>'id ASC'
            ),
            'desc'=>array(
                'order_by'=>'id DESC'
            ),
            'borrowed'=>array(
                'where'=>array('is_borrowed'=>'1')
            ),
            'Not_borrowed'=>array(
                'where'=>array('is_borrowed'=>'0')
            )
        );
        $sortKey = key($sortArr[$sortVal]);
        $conditions[$sortKey] = $sortArr[$sortVal][$sortKey];
    }
}else{
    $conditions['order_by'] = 'id DESC';
}
$books = $db->getRows($tblName,$conditions);
if(!empty($books)){
    $count = 0;
    foreach($books as $book): $count++;

        echo '<tr>';
        echo '<td>'.$book['ID'].'</td>';
        echo '<td>'.$book['name'].'</td>';
        echo '<td>'.$book['ISBN'].'</td>';
        echo '<td>'.$book['publication_year'].'</td>';
        echo '<td>'.$book['author'].'</td>';
        $status =($book['is_borrowed'] == 1)?'Borrowed':'<button type="button" class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target="#Borrowing_modal'.$book['ID'].'">Borrow</button>';
        echo '<td>'.$status;
        if($_POST['user_type']!=1)echo'<button type="button" class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target="#editBook_modal">Edit</button>';

        echo '</tr>';
    endforeach;
}else{
    echo '<tr><td colspan="5">No book(s) found...</td></tr>';
}
exit;