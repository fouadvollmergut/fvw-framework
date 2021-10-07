<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?> style="position: fixed; width: 100%; height: 100%;">
    <div id="error404" style="position: fixed; top: 50%; left: 50%; background: #fff; padding: 50px; transform: translate(-50%,-50%); width: 90%; max-width: 400px;">
      <h1><?php echo __( '404-Fehler', 'Theme' ); ?></h1>

      <p class="mth mbh"><?php echo __( 'Scheinbar existiert dieser Inhalt nicht, oder nicht mehr. Das tut uns leid.', 'Theme' ); ?></p>

      <a class="formButton" href="<?php echo home_url(); ?>" title=""><?php _e( 'Zur Startseite', 'Theme' ); ?></a>
    </div><!-- #error404 -->

    <script type="text/javascript">
      ga( 'send', 'event', '404', '<?php echo $_SERVER[ 'REQUEST_URI' ]; ?>', '<?php echo isset( $_SERVER[ 'HTTP_REFERER' ] ) ? $_SERVER[ 'HTTP_REFERER' ] : ''; ?>' );
    </script>

    <?php wp_footer(); ?>

  </body>
</html>