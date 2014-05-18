<?php
add_action('admin_init', 'wpjam_thumbnail_admin_init',99);
function wpjam_thumbnail_admin_init(){

    //自定义分类
    $custom_taxonomies = get_taxonomies(array( 'public' => true)); 
    if($custom_taxonomies){
        foreach ($custom_taxonomies as $taxonomy) {
            add_action($taxonomy.'_add_form_fields','wpjam_term_add_thumbnail_field');
            add_action($taxonomy.'_edit_form_fields','wpjam_term_edit_thumbnai_field'); 
        }
    }

    //分类
    //add_action('category_add_form_fields','wpjam_term_add_thumbnail_field');
    //add_action('edit_category_form_fields','wpjam_term_edit_thumbnai_field',10,2);

    //标签
    //add_action('post_tag_add_form_fields','wpjam_term_add_thumbnail_field');
    //add_action('post_tag_edit_form_fields','wpjam_term_edit_thumbnai_field',10,2);
        
    // 保存
    add_action('edited_term', 'wpjam_save_term_thumbnail',10, 3);  
    add_action('created_term', 'wpjam_save_term_thumbnail',10, 3);

    foreach ( get_taxonomies() as $taxonomy ) {
        add_action('manage_edit-'.$taxonomy.'_columns', 'wpjam_manage_edit_taxonomy_column_show_thumbnail');            
        add_filter('manage_'.$taxonomy.'_custom_column', 'wpjam_manage_taxonomy_custom_column_show_thumbnail', 10, 3);
    }

    function wpjam_manage_edit_taxonomy_column_show_thumbnail($columns){
        $columns['term_thumbnail'] = '缩略图';
        return $columns;
    }

    function wpjam_manage_taxonomy_custom_column_show_thumbnail($sss, $column_name,$id){
        if ($column_name == 'term_thumbnail') {
            echo wpjam_get_term_thumbnail($id,array(80,60));
        }
    }
    
}

add_filter('wpjam_pre_post_thumbnail_uri','wpjam_tag_pre_post_thumbnail_uri',10,2);
function wpjam_tag_pre_post_thumbnail_uri($post_thumbnail_uri,$post){
    $post_taxonomies = get_post_taxonomies($post);

    if($post_taxonomies){

        foreach($post_taxonomies as $taxonomy){
            if($taxonomy == 'category'){
                continue;
            }
            $terms = get_the_terms($post,$taxonomy);
            if($terms){
                foreach ($terms as $term) {
                    if($term_thumbnail = get_term_meta($term->term_id,'thumbnail',true)){
                        return $term_thumbnail;
                    }
                }
            }
        }       
        
    }
}

add_filter('wpjam_post_thumbnail_uri','wpjam_category_post_thumbnail_uri',10,2);
function wpjam_category_post_thumbnail_uri($post_thumbnail_uri,$post){
    $post_taxonomies = get_post_taxonomies($post);

    if($post_taxonomies){
        if(in_array('category',$post_taxonomies)){
            $categories = get_the_category($post);
            if($categories){
                foreach ($categories as $category) {
                    if($term_thumbnail = get_term_meta($category->term_id,'thumbnail',true)){
                        return $term_thumbnail;
                    }
                }       
            }
        }
    }
}

function wpjam_term_add_thumbnail_field($taxonomy){
    ?>
    <div class="form-field">
        <label for="thumbnail">缩略图</label>
        <input name="thumbnail" id="thumbnail" type="text" value="" style="width:80%;"/>
        <input id="thumbnail_button" class="button" type="button" value="上传图片" style="width:70px;" />
    </div>
    <?php
}

function wpjam_term_edit_thumbnai_field($term, $taxonomy=''){
    $thumbnail = get_term_meta($term->term_id,'thumbnail', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="thumbnail">缩略图</label>
        </th>
        <td>
            <input name="thumbnail" id="thumbnail" type="text" value="<?php echo $thumbnail; ?>" />
            <input id="thumbnail_button" class="button" type="button" value="上传图片" style="width:70px;" />
        </td>
    </tr>
    
    <?php
}

add_action('admin_enqueue_scripts', 'wpjam_term_thumbnail_admin_enqueue_scripts');
function wpjam_term_thumbnail_admin_enqueue_scripts() {
    if(isset($_GET['taxonomy'])){
        wp_enqueue_media();
        wp_enqueue_script('term_thumb_uploader', WPJAM_QINIUTEK_PLUGIN_URL.'/static/term_thumb_uploader.js');
    }
}

function wpjam_save_term_thumbnail($term_id, $tt_id, $taxonomy) {
    if ( isset( $_POST['thumbnail'] ) ) {
        update_term_meta($term_id,'thumbnail',$_POST['thumbnail']);
    }
}

function wpjam_get_custom_taxonomies(){
    $args = array(
        'public'   => true,
        '_builtin' => false
    );

    return get_taxonomies($args); 
}

function wpjam_has_term_thumbnail(){
    if(wpjam_get_term_thumbnail_uri()){
        return true;
    }else{
        return false;
    }
}

function wpjam_has_category_thumbnail(){
    return wpjam_has_term_thumbnail();
}

function wpjam_has_tag_thumbnail(){
    return wpjam_has_term_thumbnail();
}

function wpjam_get_term_thumbnail_uri($term=null){
    if(!$term){
        $term = get_queried_object();
    }

    if ( !$term ){
        return false;
    }

    if(is_object($term)){
        $term_id = $term->$term_id;
    }else{
        $term_id = $term;
    }
    if($term_thumbnail = get_term_meta($term_id,'thumbnail', true)){
        return $term_thumbnail;
    }

}

function wpjam_get_term_thumbnail_src($term=null, $size='thumbnail', $crop=1){

    $term_thumbnail_uri = wpjam_get_term_thumbnail_uri($term);

    if($term_thumbnail_uri){
        extract(wpjam_get_dimensions($size));

        return apply_filters('wpjam_thumbnail', $term_thumbnail_uri, $width, $height, $crop);
    }else{
        return false;
    }

}

function wpjam_get_term_thumbnail($term=null, $size='thumbnail', $crop=1, $class="wp-term-image"){

    $term_thumbnail_src = wpjam_get_term_thumbnail_src($term, $size, $crop);

    if($term_thumbnail_src){
        extract(wpjam_get_dimensions($size));

        $width_attr = $width?' width="'.$width.'"':'';
        $height_attr = $height?' height="'.$height.'"':'';

        if(!$term){
            $term = get_queried_object();
        }

        return  '<img src="'.$term_thumbnail_src.'" class="'.$class.'"'.$width_attr.$height_attr.' />';
    }else{
        return false;
    }

}

function wpjam_term_thumbnail($size='thumbnail', $crop=1, $class="wp-term-image"){
    if($term_thumbnail =  wpjam_get_term_thumbnail(null, $size, $crop, $class)){
        echo $term_thumbnail;
    }
}

function wpjam_category_thumbnail($size='thumbnail', $crop=1, $class="wp-category-image"){
    wpjam_term_thumbnail($size,$crop,$class);
}

function wpjam_tag_thumbnail($size='thumbnail', $crop=1, $class="wp-tag-image"){
    wpjam_term_thumbnail($size,$crop,$class);
}
