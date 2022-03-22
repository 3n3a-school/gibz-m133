<?php

namespace M133\Controllers;

class CategoriesController extends \M133\Controller {
    public function getAllCategories ( ) {

        $cat_sql = "SELECT id, name FROM category";

        $query_data = $this->db->queryData($cat_sql, [], "AllCategories");
        $data = ! empty ($query_data) ? $query_data : NULL;

        if ( ! empty($data) )
            return $data;
        return NULL;
    }

    public function getCatName( $id ) {
        $get_cat_sql = "SELECT name FROM category WHERE id = ?";

        $query_data = $this->db->queryData($get_cat_sql, [$id], "GetCatName for " . $id);
        $data = ! empty ($query_data) ? $query_data[0] : NULL;

        if ( ! empty($data) )
            return $data['name'];
        return false;
    }
}