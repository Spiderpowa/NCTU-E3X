<?php 
function log_db($action, $label, $value = NULL){
  $data = array(
    'action' => $action,
    'label' => $label,
    'value' => $value
   );
  get_instance()->db->insert('log', $data);
}