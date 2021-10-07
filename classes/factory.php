<?php

  class FVW_FACTORY {
    public $tools = null;

    public $settings = null;

    function tools() {
      if (!$this->tools):
        $this->tools = new FVW_TOOLS;
      endif;

      return $this->tools;
    }

    # Returns a theme setting
    function setting( $path ) {
      if ($this->settings === null):
        $locate = locate_template( 'settings.php' );

        if ($locate):
          require $locate;
          $this->settings = $settings;
        else:
          $this->settings = false;
        endif;
      endif;

      if (!$this->settings): 
        return false;
      endif;

      $exploded = explode( '/', $path );

      $temp = $this->settings;

      foreach ($exploded as $key):
        if (!isset( $temp[ $key ] )): 
          return null;
        endif;

        $temp = $temp[ $key ];
      endforeach;

      return $temp;
    }

    # Throw error
    function error( $message ) {
      trigger_error( $message );
    }

    # Shows maintenance tempalte
    function maintenance() {
      if ($locate = locate_template( 'maintenance.php' )):
        require $locate;
      else:
        require FVW_FRAMEWORK_BASE_PATH . 'includes/maintenance.php';
      endif;
    }

    # Creates a Google Anayltics Events tracking snippet
    function track( $category, $action, $label = '' ) {
      if( fvw()->setting( 'integration/google_analytics' ) ):
    ?>
      <script>
        gtag('event', '<?php echo $action ?>', {
          'event_category': '<?php echo $category ?>',
          'event_label': '<?php echo $label ?>',
        });
      </script>
    <?php
      endif;
    }
  }
