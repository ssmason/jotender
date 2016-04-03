<?php

/**
 * List
 *
 * @package Framework\List
 */
if ( !class_exists( 'bc_scbList' ) ) {
    // Generic container for easily manipulating an ordered associative array
    class bc_scbList
    {
        protected  $items = array() ;
        public function add( $id, $payload )
        {
            // TODO: allow overwrite or have a replace() method ?
            $this->items[$id] = $payload;
        }
        
        public function add_before( $ref_id, $id, $payload )
        {
            $new_array = array();
            $found = false;
            foreach ( $this->items as $key => $value ) {
                
                if ( $key == $ref_id ) {
                    $new_array[$id] = $payload;
                    $found = true;
                }
                
                $new_array[$key] = $value;
            }
            if ( !$found ) {
                $new_array[$id] = $payload;
            }
            $this->items = $new_array;
        }
        
        public function add_after( $ref_id, $id, $payload )
        {
            $new_array = array();
            $found = false;
            foreach ( $this->items as $key => $value ) {
                $new_array[$key] = $value;
                
                if ( $key == $ref_id ) {
                    $new_array[$id] = $payload;
                    $found = true;
                }
            
            }
            if ( !$found ) {
                $new_array[$id] = $payload;
            }
            $this->items = $new_array;
        }
        
        public function contains( $id )
        {
            return isset( $this->items[$id] );
        }
        
        public function is_empty()
        {
            return empty($this->items);
        }
        
        public function remove( $id )
        {
            unset( $this->items[$id] );
        }
        
        public function get( $id )
        {
            return $this->items[$id];
        }
        
        public function get_all()
        {
            return $this->items;
        }
        
        public function get_first()
        {
            reset( $this->items );
            return current( $this->items );
        }
        
        public function get_first_key()
        {
            reset( $this->items );
            return key( $this->items );
        }
        
        public function get_last()
        {
            end( $this->items );
            return current( $this->items );
        }
        
        public function get_last_key()
        {
            end( $this->items );
            return key( $this->items );
        }
        
        public function get_by_index( $index )
        {
            $values = array_values( $this->items );
            return $values[$index];
        }
        
        public function get_by_index_key( $index )
        {
            $keys = array_keys( $this->items );
            return $keys[$index];
        }
        
        public function get_before( $ref_id )
        {
            $last_item = false;
            foreach ( $this->items as $key => $value ) {
                if ( $key == $ref_id ) {
                    return $last_item;
                }
                $last_item = $value;
            }
            return false;
        }
        
        public function get_key_before( $ref_id )
        {
            $last_item = false;
            foreach ( $this->items as $key => $value ) {
                if ( $key == $ref_id ) {
                    return $last_item;
                }
                $last_item = $key;
            }
            return false;
        }
        
        public function get_after( $ref_id )
        {
            $found = false;
            foreach ( $this->items as $key => $value ) {
                
                if ( $key == $ref_id ) {
                    $found = true;
                } else {
                    if ( $found == true ) {
                        return $value;
                    }
                }
            
            }
            return false;
        }
        
        public function get_key_after( $ref_id )
        {
            $found = false;
            foreach ( $this->items as $key => $value ) {
                
                if ( $key == $ref_id ) {
                    $found = true;
                } else {
                    if ( $found == true ) {
                        return $key;
                    }
                }
            
            }
            return false;
        }
    
    }
}