<?php
$data = file_get_contents( 'php://input' );
$objData = json_decode( $data );

$wp_load_dir = isset( $objData->wp_load_dir ) ? $objData->wp_load_dir : false;
if( !$wp_load_dir || !file_exists( $wp_load_dir . 'wp-load.php' ) ) {
    $wp_load_dir = '../../../../../../../';
}
require_once( $wp_load_dir . 'wp-load.php' );

if( !current_user_can( 'manage_options' ) ) die( "You shouldn't be here" );

class WPUEP_Parser {

    protected $blocks = array();
    protected $fonts = array();

    protected $template_blocks = array();
    public $template;

    public function __construct( $blocks )
    {
        // Get blocks that are part of the template
        foreach( $blocks as $block ) {
            if( ( !isset( $block->deleted ) || $block->deleted == false ) && isset( $block->index ) )
            {
                $this->blocks[$block->index] = $block;
            }
        }

        // Parse all blocks
        foreach( $this->blocks as $block ) {

            // Only parse if all parents exist (could have been deleted)
            $parent = $block->parent;

            while( $parent != -1 && isset( $this->blocks[$parent] ) ) {
                $parent = $this->blocks[$parent]->parent;
            }

            if( $parent == -1 ) $this->parseBlock($block);
        }

        // Get the generated template
        $this->template = new WPUEP_Template( $this->template_blocks, $this->fonts );
    }

    protected function parseBlock( $block )
    {

//Original	## $template_block = call_user_func( array( $this, 'parseBlock' . ucfirst( $block->type ) ), $block );
	
       $template_block = call_user_func( array( $this, 'parseBlock' . ucfirst( $block->type ) ), $block );
        $template_block->add_settings( $block );

        // Block positioning
        if( $block->type != 'container' ) {
            $template_block->parent( $block->parent );
            $template_block->row( $block->row );
            $template_block->column( $block->column );
            $template_block->order( $block->order );
        }

        // Google Web Fonts
        if( isset( $block->fontFamilyType ) && $block->fontFamilyType == 'gwf' ) {
            $fonts = $this->fonts;
            $fonts[] = $block->fontFamilyGWF;
            $this->fonts = $fonts;
        }

        $this->template_blocks[$block->index] = $template_block;
    }

    protected function parseBlockContainer( $block )
    {
        return new WPUEP_Template_Container();
    }

    /**
     * Layout blocks
     */

    protected function parseBlockRows( $block )
    {
        $rows = intval( $block->rows );
        $heights = $this->getRowHeights( $block );

        $template_rows = new WPUEP_Template_Rows();
        return $template_rows->rows($rows)->height($heights);
    }

    protected function getRowHeights( $block )
    {
        $heights = array();

        for($i = 0; $i < $block->rows; $i++)
        {
            $rowtype = 'rowtype' . $i;

            if( $block->{$rowtype} == 'px' ) {
                $rowheight = 'row' . $i;
                $heights[$i] = $block->{$rowheight} . 'px';
            } else {
                $heights[$i] = 'auto';
            }
        }

        return $heights;
    }

    protected function parseBlockColumns( $block )
    {
        $cols = intval( $block->columns );
        $widths = $this->getColWidths( $block );

        $template_columns = new WPUEP_Template_Columns();
        $template_block = $template_columns->columns($cols)->width($widths);

        if( isset( $block->columnsResponsive ) && $block->columnsResponsive ) {
            $template_block->responsive( true );

            if( isset( $block->columnsResponsiveReverse ) && $block->columnsResponsiveReverse ) {
                $template_block->mobile_reverse( true );
            }
        }

        return $template_block;
    }

    protected function getColWidths( $block )
    {
        $widths = array();

        for($i = 0; $i < $block->columns; $i++)
        {
            $coltype = $block->{'columntype' . $i};
            $colwidth = $block->{'column' . $i};

            if( $colwidth == '' || $colwidth == 0 ) {
                $widths[$i] = 'auto';
            } else {
                $widths[$i] = $colwidth . $coltype;
            }
        }

        return $widths;
    }

    protected function parseBlockTable( $block )
    {
        $rows = intval( $block->rows );
        $heights = $this->getRowHeights( $block );
        $cols = intval( $block->columns );
        $widths = $this->getColWidths( $block );

        $template_table = new WPUEP_Template_Table();
        return $template_table->rows($rows)->height($heights)->columns($cols)->width($widths);
    }

    protected function parseBlockBox( $block )
    {
        return new WPUEP_Template_Box();
    }

    /**
     * General Blocks
     */
    protected  function parseBlockDate( $block )
    {
        $template_date = new WPUEP_Template_Date();
        $format = isset( $block->dateFormat ) ? $block->dateFormat : '';
        return $template_date->format($format);
    }

    protected  function parseBlockImage( $block )
    {
        $image = new WPUEP_Template_Image();

        $url = '';

        if( isset( $block->imagePreset ) && $block->imagePreset ) {
            return $image->preset( $block->imagePreset );
        }

        if( isset( $block->imageUrl ) && $block->imageUrl ) {
            $url = $block->imageUrl;
        }

        return $image->url($url);
    }

    protected  function parseBlockTitle( $block )
    {
        $template_title = new WPUEP_Template_Title();
        return $template_title->tag($block->tag)->text($block->text);
    }

    protected  function parseBlockSpace( $block )
    {
        $template_space = new WPUEP_Template_Space();
        return $template_space->non_breaking($block->nonBreaking);
    }

    protected  function parseBlockParagraph( $block )
    {
        $template_paragraph = new WPUEP_Template_Paragraph();
        return $template_paragraph->text( $block->paragraph );
    }

    protected  function parseBlockLink( $block )
    {
        $link = new WPUEP_Template_Link();
        $link->text($block->text)->url($block->linkUrl);

        if( $block->linkNewPage ) {
            $link->target('_blank');
        }

        return $link;
    }

    protected  function parseBlockCode( $block )
    {
        $template_code = new WPUEP_Template_Code();
        return $template_code->text($block->text);
    }

    /**
     * Exercise Field Blocks
     */

    protected function parseBlockExerciseImage( $block )
    {
        $image = new WPUEP_Template_Exercise_Image();

        if( !isset( $block->width ) || !isset( $block->widthType ) || !isset( $block->height ) || !isset( $block->heightType )
            || $block->widthType != 'px' || $block->heightType != 'px' ) {
            $thumb = 'full';
        } else {
            $width = intval( $block->width );
            $height = intval( $block->height );

            $thumb = array($width, $height);
        }

        if( isset( $block->imageCrop ) && $block->imageCrop ) {
            $image->crop( true );
        }

        return $image->thumbnail( $thumb );
    }

    protected function parseBlockExerciseAuthor( $block )             { return new WPUEP_Template_Exercise_Author(); }
    protected function parseBlockExercisePostContent( $block )        { return new WPUEP_Template_Exercise_Post_Content(); }
    protected  function parseBlockExerciseDate( $block )
    {
        $template_date = new WPUEP_Template_Exercise_Date();
        $format = isset( $block->dateFormat ) ? $block->dateFormat : '';
        return $template_date->format($format);
    }

    protected function parseBlockExerciseTitle( $block )              { return new WPUEP_Template_Exercise_Title(); }
    protected function parseBlockExerciseDescription( $block )        { return new WPUEP_Template_Exercise_Description(); }
    protected function parseBlockExerciseStars( $block )              { return new WPUEP_Template_Exercise_Stars(); }
    protected function parseBlockExerciseServings( $block )           { return new WPUEP_Template_Exercise_Servings(); }
    protected function parseBlockExerciseServingsType( $block )       { return new WPUEP_Template_Exercise_Servings_Type(); }
    protected function parseBlockExerciseCookTime( $block )           { return new WPUEP_Template_Exercise_Cook_Time(); }
    protected function parseBlockExerciseCookTimeUnit( $block )       { return new WPUEP_Template_Exercise_Cook_Time_Text(); }
    protected function parseBlockExercisePrepTime( $block )           { return new WPUEP_Template_Exercise_Prep_Time(); }
    protected function parseBlockExercisePrepTimeUnit( $block )       { return new WPUEP_Template_Exercise_Prep_Time_Text(); }
    protected function parseBlockExercisePassiveTime( $block )        { return new WPUEP_Template_Exercise_Passive_Time(); }
    protected function parseBlockExercisePassiveTimeUnit( $block )    { return new WPUEP_Template_Exercise_Passive_Time_Text(); }
    protected function parseBlockExerciseNotes( $block )              { return new WPUEP_Template_Exercise_Notes(); }
    protected function parseBlockExerciseLink( $block )               { return new WPUEP_Template_Exercise_Link(); }

    protected function parseBlockExerciseTags( $block )               {
        $tags = new WPUEP_Template_Exercise_Tags();
        if( $block->actAsList === true ) {
            $tags->is_list( true );
            $tags->list_style( $block->listStyle );
        }

        return $tags;
    }

    protected function parseBlockExerciseIngredients( $block )       {
        $ingredients = new WPUEP_Template_Exercise_Ingredients();
        if( isset( $block->showGroups ) && $block->showGroups != 'all' ) {
            $list = isset( $block->showGroupsList ) ? $block->showGroupsList : '';
            $ingredients->groups( $block->showGroups, $list );
        }
        return $ingredients;
    }

    protected function parseBlockExerciseInstructions( $block )       {
        $instructions = new WPUEP_Template_Exercise_Instructions();
        if( $block->exerciseInstructionsImages === false ) {
            $instructions->show_images(false);
        }
        if( isset( $block->showGroups ) && $block->showGroups != 'all' ) {
            $list = isset( $block->showGroupsList ) ? $block->showGroupsList : '';
            $instructions->groups( $block->showGroups, $list );
        }
        return $instructions;
    }

    protected function parseBlockExerciseCustomField( $block )       {
        $custom_field = new WPUEP_Template_Exercise_Custom_Field();
        if( isset( $block->exerciseCustomFieldKey ) ) $custom_field->key( $block->exerciseCustomFieldKey );
        return $custom_field;
    }

    /**
     * Exercise Sub Field Blocks
     */
    protected function parseBlockExerciseIngredientGroup( $block )     { return new WPUEP_Template_Exercise_Ingredient_Group(); }
    protected function parseBlockExerciseIngredientName( $block )      { return new WPUEP_Template_Exercise_Ingredient_Name(); }
    protected function parseBlockExerciseIngredientNotes( $block )     { return new WPUEP_Template_Exercise_Ingredient_Notes(); }
    protected function parseBlockExerciseIngredientQuantity( $block )  { return new WPUEP_Template_Exercise_Ingredient_Quantity(); }
    protected function parseBlockExerciseIngredientUnit( $block )      { return new WPUEP_Template_Exercise_Ingredient_Unit(); }
    protected function parseBlockExerciseInstructionGroup( $block )    { return new WPUEP_Template_Exercise_Instruction_Group(); }
    protected function parseBlockExerciseInstructionText( $block )     { return new WPUEP_Template_Exercise_Instruction_Text(); }
    protected function parseBlockExerciseTagName( $block )             { return new WPUEP_Template_Exercise_Tag_Name(); }
    protected function parseBlockExerciseTagTerms( $block )            { return new WPUEP_Template_Exercise_Tag_Terms(); }

    protected function parseBlockExerciseIngredientContainer( $block )
    {
        $ingredients = new WPUEP_Template_Exercise_Ingredient_Container();
        if( $block->actAsList === true ) {
            $ingredients->is_list( true );
            $ingredients->list_style( $block->listStyle );
        }

        return $ingredients;
    }

    protected function parseBlockExerciseInstructionContainer( $block )
    {
        $instructions = new WPUEP_Template_Exercise_Instruction_Container();
        if( $block->actAsList === true ) {
            $instructions->is_list( true );
            $instructions->list_style( $block->listStyle );
        }

        return $instructions;
    }

    protected function parseBlockExerciseInstructionImage( $block )
    {
        $image = new WPUEP_Template_Exercise_Instruction_Image();

        if( !isset( $block->width ) || !isset( $block->widthType ) || !isset( $block->height ) || !isset( $block->heightType )
            || $block->widthType != 'px' || $block->heightType != 'px' ) {
            $thumb = 'full';
        } else {
            $width = intval( $block->width );
            $height = intval( $block->height );

            $thumb = array($width, $height);
        }

        if( isset( $block->imageCrop ) && $block->imageCrop ) {
            $image->crop( true );
        }

        return $image->thumbnail( $thumb );
    }

    /**
     * Exercise Functionality Blocks
     */

    protected function parseBlockPrintButton( $block ) {
        $print_button = new WPUEP_Template_Exercise_Print_Button();

        if( isset( $block->icon ) && $block->icon ) {
            $print_button->icon( 'fa-' . $block->icon );
        }

        return $print_button;
    }
    protected function parseBlockAddToShoppingList( $block ) {
        $add_to_shopping_list = new WPUEP_Template_Exercise_Add_To_Shopping_List();

        if( isset( $block->icon ) && $block->icon ) {
            $add_to_shopping_list->icon( 'fa-' . $block->icon );
        }

        return $add_to_shopping_list;
    }
    protected function parseBlockFavoriteExercise( $block ) {
        $favorite_exercise = new WPUEP_Template_Exercise_Favorite();

        if( isset( $block->icon ) && $block->icon ) {
            $favorite_exercise->icon( 'fa-' . $block->icon );
        }
        if( isset( $block->iconAlt ) && $block->iconAlt ) {
            $favorite_exercise->iconAlt( 'fa-' . $block->iconAlt );
        }

        return $favorite_exercise;
    }
    protected function parseBlockServingsChanger( $block )          { return new WPUEP_Template_Exercise_Servings_Changer(); }
    protected function parseBlockUnitChanger( $block )              { return new WPUEP_Template_Exercise_Unit_Changer(); }
    protected function parseBlockExerciseSharing( $block )            { return new WPUEP_Template_Exercise_Sharing(); }

    /**
     * Nutritional Information Blocks
     */
    protected function parseBlockNutritionLabel( $block )       { return new WPUEP_Template_Exercise_Nutrition_Label(); }
    protected function parseBlockCalories( $block )             { return $this->parseNutritionBlock( 'calories', $block->showUnit ); }
    protected function parseBlockTotalFat( $block )             { return $this->parseNutritionBlock( 'fat', $block->showUnit, $block->nutritionPercentage ); }
    protected function parseBlockSaturatedFat( $block )         { return $this->parseNutritionBlock( 'saturated_fat', $block->showUnit, $block->nutritionPercentage ); }
    protected function parseBlockTransFat( $block )             { return $this->parseNutritionBlock( 'trans_fat', $block->showUnit ); }
    protected function parseBlockPolyunsaturatedFat( $block )   { return $this->parseNutritionBlock( 'polyunsaturated_fat', $block->showUnit ); }
    protected function parseBlockMonounsaturatedFat( $block )   { return $this->parseNutritionBlock( 'monounsaturated_fat', $block->showUnit ); }
    protected function parseBlockCholesterol( $block )          { return $this->parseNutritionBlock( 'cholesterol', $block->showUnit, $block->nutritionPercentage ); }
    protected function parseBlockSodium( $block )               { return $this->parseNutritionBlock( 'sodium', $block->showUnit, $block->nutritionPercentage ); }
    protected function parseBlockPotassium( $block )            { return $this->parseNutritionBlock( 'potassium', $block->showUnit, $block->nutritionPercentage ); }
    protected function parseBlockTotalCarbohydrates( $block )   { return $this->parseNutritionBlock( 'carbohydrate', $block->showUnit, $block->nutritionPercentage ); }
    protected function parseBlockDietaryFiber( $block )         { return $this->parseNutritionBlock( 'fiber', $block->showUnit, $block->nutritionPercentage ); }
    protected function parseBlockSugars( $block )               { return $this->parseNutritionBlock( 'sugar', $block->showUnit ); }
    protected function parseBlockProtein( $block )              { return $this->parseNutritionBlock( 'protein', $block->showUnit, $block->nutritionPercentage ); }
    protected function parseBlockVitaminA( $block )             { return $this->parseNutritionBlock( 'vitamin_a', $block->showUnit ); }
    protected function parseBlockVitaminC( $block )             { return $this->parseNutritionBlock( 'vitamin_c', $block->showUnit ); }
    protected function parseBlockCalcium( $block )              { return $this->parseNutritionBlock( 'calcium', $block->showUnit ); }
    protected function parseBlockIron( $block )                 { return $this->parseNutritionBlock( 'iron', $block->showUnit ); }

    protected function parseNutritionBlock( $field, $unit, $percentage = false ) {
        $nutrition = new WPUEP_Template_Exercise_Nutrition();
        return $nutrition->field( $field )->unit( $unit )->percentage( $percentage );
    }

    /**
     * Partner Blocks
     */

    protected function parseBlockBigOven( $block )      { return new WPUEP_Template_Bigoven(); }
    protected function parseBlockChicory( $block )      { return new WPUEP_Template_Chicory(); }
    protected function parseBlockFoodFanatic( $block )  { return new WPUEP_Template_Food_Fanatic(); }
    protected function parseBlockYummly( $block )  { return new WPUEP_Template_Yummly(); }

    /**
     * Social Blocks
     */
    protected function parseBlockTwitter( $block ) {
        $socialBlock = new WPUEP_Template_Twitter();
        $socialBlock->layout( $block->socialLayout );
        return $socialBlock;
    }
    protected function parseBlockFacebook( $block ) {
        $socialBlock = new WPUEP_Template_Facebook();
        $socialBlock->layout( $block->socialLayout );
        if( isset( $block->facebookShare ) && $block->facebookShare ) {
            $socialBlock->share( 'true' );
        }
        return $socialBlock;
    }
    protected function parseBlockGoogle( $block ) {
        $socialBlock = new WPUEP_Template_Google();
        $socialBlock->layout( $block->socialLayout );
        return $socialBlock;
    }
    protected function parseBlockPinterest( $block ) {
        $socialBlock = new WPUEP_Template_Pinterest();
        $socialBlock->layout( $block->socialLayout );
        return $socialBlock;
    }
    protected function parseBlockStumbleupon( $block ) {
        $socialBlock = new WPUEP_Template_Stumbleupon();
        $socialBlock->layout( $block->socialLayout );
        return $socialBlock;
    }
    protected function parseBlockLinkedin( $block ) {
        $socialBlock = new WPUEP_Template_Linkedin();
        $socialBlock->layout( $block->socialLayout );
        return $socialBlock;
    }

    /**
     * General
     */

    public function preview()
    {
        $exercise_id = WPUltimateExercise::option('exercise_template_editor_exercise');

        if(!$exercise_id) {
            $query = WPUltimateExercise::get()->helper( 'query_exercises' );
            $exercises = $query->post_status( array( 'publish', 'private' ) )->order_by( 'date' )->order( 'ASC' )->limit(1)->get();
            if( isset( $exercises[0] ) ) $exercise_id = $exercises[0]->ID();
        }

        if( $exercise_id && 'exercise' == get_post_type( $exercise_id ) ) {
            $exercise = new WPUEP_Exercise( $exercise_id );

            $src = $exercise->link();
            if( strpos( $src, '?' ) ) {
                $src = $src . '&wpuep_template_editor_preview=' . $exercise_id;
            } else {
                $src = $src . '?wpuep_template_editor_preview=' . $exercise_id;
            }

            update_option( 'wpuep_custom_template_preview', $this->template );

            echo '<iframe id="preview-frame" src="'.$src.'" onload="resizeFrame(this)"/>';
        } else {
            echo '<div style="text-align: center;">';
            _e( 'Please set a Preview Exercise in the settings first.', 'wp-eultimate-post-grid' );
            echo '</div>';
        }
    }

    public function save( $save_as_new, $id, $new_name )
    {
        if( $save_as_new ) {
            $new_id = WPUltimateExercise::addon( 'custom-templates' )->add_template( $new_name, $this->template );
            echo json_encode( array(
                'id' => $new_id,
                'name' => $new_name,
                'active' => '',
            ) );
        } else {
            WPUltimateExercise::addon( 'custom-templates' )->update_template( $id, $this->template );
            echo json_encode(false);
        }
    }

    public function export()
    {
        echo $this->template->encode();
    }
}
$parser = new WPUEP_Parser( $objData->template->blocks );

if( isset( $objData->code ) ) {
    $template = unserialize( base64_decode( $objData->code ) );

    if( is_object( $template ) ) {
        $parser->template = $template;
    } else {
        die( json_encode( array(
            'error' => true,
        ) ) );
    }
}

if( isset( $objData->saveAsNew ) ) {
    $parser->save( $objData->saveAsNew, $objData->template->id, $objData->newName );
} else if( isset( $objData->export ) ) {
    $parser->export();
} else {
    $parser->preview();
}