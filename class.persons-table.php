<?php
if( ! class_exists("WP_List_Table") ) {
    require_once ( ABSPATH."wp-admin/includes/class-wp-list-table.php");
}

Class Persons_Table extends WP_List_Table{
    private $_items;
    function __construct($args = array()) {
        parent::__construct($args);
    }

    function  set_data($data){

        $this->_items = $data;
    }

    function get_columns() {
        return [
            'cb'    => "<input type='checkbox'>",
            'name'  => __( 'Name', 'datatable' ),
            'sex'   => __( 'Gender', 'datatable' ),
            'email' => __( 'E-Mail', 'datatable' ),
            'age'   => __( 'Age', 'datatable' ),
        ];
    }

    function get_sortable_columns() {
        return [
            'age'=>["age",true],
            'name'=>["name",true],
        ];
    }

    function extra_tablenav( $which ) {
        if ( 'top' == $which ){
       ?>
        <div class="actions alignleft">
            <select name="filter_s" id="filter_s">
                <option value="all"> All </option>
                <option value="M"> Male </option>
                <option value="F"> Femal </option>
            </select>
            <?php submit_button( __( 'Filter', 'tabledata' ), 'button', 'submit', false ); ?>
        </div>
    <?php
        }

    }

    function column_cb( $item ) {
        return "<input type='checkbox' value='{$item['id']}'>";
    }

    function column_email( $item ) {
        return "<strong>{$item['email']}</strong>";
    }
    function column_age( $item ) {
        return "<em>{$item['age']}</em>";
    }

    function prepare_items() {
        $per_page              = 4;
        $total_page            = count( $this->_items );
        $paged                 = $_REQUEST['paged'] ?? 1;
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
        $data_chunks           = array_chunk( $this->_items, $per_page);
        $this->items           = $data_chunks[$paged-1];

        $this->set_pagination_args([
            'total_items' => $total_page,
            'per_pge'     => $per_page,
            'total_pages' => ceil( $total_page / $per_page ),
        ]);
    }

    function column_default($item, $column_name) {
        return $item[$column_name];
    }
}