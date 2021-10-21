<?php

  class FVW_FIELD {

    /* PROPERTIES */

    public $form = null;


    /* CONSTRUCTOR */

    public function __construct( $key, $form, $settings = false ) {

      // Rewrite email for honeypot spam protection
      $key = ( $key == 'email' ) ? 'honeypot' : $key;

      // Default settings
      $defaults = array(
        'type' => 'text',
        'key' => $key,
        'label' => 'Label',
        'id' => false, // Switches to default if not set
        'name' => false, // Switches to key if not set
        'required' => true,
        'disabled' => false,
        'value' => '',
        'default' => '',
        'placeholder' => '',
        'min' => '0', // For number field
        'max' => false, // For number field
        'step' => '0.01', // For number field
        'dateMin' => 'today', // For date field
        'dateMax' => null, // For date field
        'dateDisable' => null, // For date field
        'dateEnable' => null, // For date field
        'rows' => '3', // For textarea field
        'items' => null, // For select or radio items
        'validate' => null, // Automatic validations or custom callback function
        'sanitize' => null, // Automatic sanitation or custom callback function
      );

      // Merge defaults with settings
      $final = wp_parse_args( $settings, $defaults );

      // Disable required for notes
      if( $final[ 'type' ] == 'note' ) $final[ 'required' ] = false;

      // Converting type aliases
      if( $final[ 'type' ] == 'area' ) $final[ 'type' ] = 'textarea';
      if( $final[ 'type' ] == 'datetime-local' ) $final[ 'type' ] = 'datetime';

      // Assign settings to properties
      foreach( $final AS $key => $value ) $this->$key = $value;

      // Assign form object
      $this->form = $form;
    }


    /* HELPER */

    # Returns the field key
    public function key() {
      return $this->key;
    }

    # Returns the field name
    public function name() {
      return $this->name ?: $this->id();
    }

    # Set or get the field type
    public function type( $set = null ) {
      if( $set !== null ) $this->type = $set;

      return $this->type;
    }

    # Returns the field ID
    public function id() {
      return $this->id ?: $this->form()->key() . '_' . $this->key;
    }

    # Returns the parent form
    public function form() {
      return $this->form;
    }

    # Set or get a field default
    public function default( $set = null ) {
      if( $set !== null ) $this->default = $set;

      return $this->default;
    }

    # Set or get a field value
    public function value( $set = null ) {
      if( $set !== null ) $this->value = $set;

      return $this->value;
    }

    # Returns errors for this name
    public function error( $set = null ) {
      return $this->form()->error( $this->key, $set );
    }

    # Returns a set class
    public function class( $class ) {
      return $this->form()->class( $class );
    }


    /* RENDERING */

    # Create label attribute
    public function label_attributes( $attributes = null ) {

      // Default atts
      $defaults = array(
        'class' => $this->class( 'label' ),
        'for' => $this->id(),
      );

      // Merge defaults with atts
      $attributes = wp_parse_args( $attributes, $defaults );

      // Return atts
      return fvw()->tools()->attributes( $attributes );
    }

    # Create label output
    public function label( $atts = null ) {
      echo '<label ' . $this->label_attributes( $atts ) . '>';
      echo $this->label;
      if( $this->required ) $this->form()->required();
      echo '</label>';
    }

    # Renders the field
    public function render( $class = null ) {

      // Required attribute
      $required = $this->required ? 'required' : '';

      // Wrapper open
      if( $this->type() != 'hidden' ):

        // Classes
        $class_handler = array();
        $class_handler[] = $this->class( 'part' );

        if( $this->error() ) $class_handler[] = $this->class( 'error' );

        if( $this->required ) $class_handler[] = $this->class( 'part' ) . 'Required';

        if( $class !== null ) $class_handler[] = $class;

        // Attibutes
        $attributes = array(
          'class' => trim( implode( ' ', $class_handler ) ),
          'data-type' => $this->type(),
          'data-key' => $this->key(),
        );

        // Wrapper
        echo '<div ' . fvw()->tools()->attributes( $attributes ) . '>';
      endif;

      // Iterate through types
      switch( $this->type() ):

        // Note
        case 'note':
          echo '<div class="' . $this->class( 'note' ) . '" id="' . $this->id() . '" />';
          echo $this->label;
          echo '</div>';
        break;

        // Select
        case 'select':
          $this->label();

          // Start: Handling for deprecated (since v17) options parameter
          if( isset( $this->options ) AND !isset( $this->items ) ):
            $items = $this->options;

            fvw()->error( 'The FVW_FIELD options property is deprecated. Use items instead.' );
          else:
            $items = $this->items;
          endif;
          // End: Handling for deprecated (since v17) options parameter

          echo '<select class="' . $this->class( 'select' ) . '" id="' . $this->id() . '" name="' . $this->name() . '" ' . $required . ' />';

          $i = 0;

          foreach( $items AS $itemValue => $itemName ): $i++; 
            if( $this->value() == '' AND $i == 1 ):
              $selected = 'selected="selected"';
            elseif( $itemValue == $this->value() ):
              $selected = 'selected="selected"';
            else:
              $selected = '';
            endif;

            echo '<option value="' . $itemValue . '" ' . $selected . '>' . $itemName . '</option>';
          endforeach;

          echo '</select>';
        break;

        // Radio
        case 'radio':
          $this->label();

          // Start: Handling for deprecated (since v17) options parameter
          if( isset( $this->options ) AND !isset( $this->items ) ):
            $items = $this->options;

            fvw()->error( 'The FVW_FIELD options property is deprecated. Use items instead.' );
          else:
            $items = $this->items;
          endif;
          // End: Handling for deprecated (since v17) options parameter

          echo '<ul id="' . $this->id() . $i . '">';

          $i = 0; 

          foreach( $items AS $itemValue => $itemName ): $i++; 
            if( $this->value() == '' AND $i == 1 ):
              $selected = 'checked="checked"';
            elseif( $itemValue == $this->value() ):
              $selected = 'checked="checked"';
            else:
              $selected = '';
            endif;

            echo '<li>';
            echo '<input id="' . $this->id() . $i . '" class="' . $this->class( 'radio' ) . '" type="' . $this->type() . '" name="' . $this->name() . '" ' . $selected . ' ' . $required . ' />';
            echo '<label for="' . $this->id() . $i . '">' . $itemName . '</label>';
            echo '</li>';
          endforeach;

          echo '</ul>';
        break;

        // Checkbox
        case 'checkbox':
          $selected = $this->value() ? 'checked="checked"' : '';

          echo '<input class="' . $this->class( 'checkbox' ) . '" id="' . $this->id() . '" type="' . $this->type() . '" name="' . $this->name() . '" ' . $selected . ' ' . $required . ' />';

          $this->label();
        break;

        // Textarea ('area' as alias is possible)
        case 'textarea':
          $this->label();

          echo '<textarea rows="' . $this->rows . '" class="' . $this->class( 'textarea' ) . '" id="' . $this->id() . '" name="' . $this->name() . '" ' . $required . ' />';
          echo $this->value();
          echo '</textarea>';
        break;

        // Number
        case 'number':
          $this->label();

          $placeholder = $this->placeholder ? 'placeholder="' . $this->placeholder . '"' : '';
          $min = $this->min !== false ? 'min="' . $this->min . '"' : '';
          $max = $this->max !== false ? 'max="' . $this->max . '"' : '';
          $step = $this->step !== false ? 'step="' . $this->step . '"' : '';

          echo '<input class="' . $this->class( 'text' ) . '" id="' . $this->id() . '" type="' . $this->type() . '" name="' . $this->name() . '" value="' . $this->value() . '" ' . $placeholder . ' ' . $min . ' ' . $max . ' ' . $step . ' ' . $required . ' />';
        break;

        // Date, time and datetime (Flatpickr JS)
        case 'date':
        case 'time':
        case 'datetime':
        case 'daterange':
          $this->label();

          $placeholder = $this->placeholder ? 'placeholder="' . $this->placeholder . '"' : '';

          // Prepare date disable and enable
          $dateDisable = is_array( $this->dateDisable ) ? implode( ',', $this->dateDisable ) : '';
          $dateEnable = is_array( $this->dateEnable ) ? implode( ',', $this->dateEnable ) : '';

          echo '<div class="fvw_flatpickr" data-type="' . $this->type() . '" data-datemin="' . $this->dateMin . '" data-datemax="' . $this->dateMax . '" data-dateenable="' . $dateEnable . '" data-datedisable="' . $dateDisable . '">';
          echo '<input class="' . $this->class( 'text' ) . '" id="' . $this->id() . '" type="text" name="' . $this->name() . '" value="' . $this->value() . '" ' . $placeholder . ' ' . $required . ' />';
          echo '</div>';
        break;

        // Hidden
        case 'hidden':
          echo '<input class="' . $this->class( 'hidden' ) . '" id="' . $this->id() . '" type="' . $this->type() . '" name="' . $this->name() . '" value="' . $this->value() . '" />';
        break;

        // Text and others
        default:
          $this->label();

          $placeholder = $this->placeholder ? 'placeholder="' . $this->placeholder . '"' : '';

          echo '<input class="' . $this->class( 'text' ) . '" id="' . $this->id() . '" type="' . $this->type() . '" name="' . $this->name() . '" value="' . $this->value() . '" ' . $placeholder . ' ' . $required . ' />';
        break;
      endswitch;

      // Wrapper close
      if( $this->type() != 'hidden' ) echo '</div>';
    }
  }
