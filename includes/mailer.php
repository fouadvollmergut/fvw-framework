<?php

  // Function for sending styled mails
  function stylemail( $recipient_mail, $subject, $message, $sender_mail, $sender_name, $attachments = false ) {
    require_once ABSPATH . WPINC . '/class-phpmailer.php';
    require_once ABSPATH . WPINC . '/class-smtp.php';

    // Init PHP-Mailer
    $mailer = new PHPMailer();

    // Set to UTF-8
    $mailer->CharSet = 'UTF-8';

    // Determine sending type
    if( fvw()->setting( 'integration/smtp' ) ) {

      // Set debug if enabled
      if( fvw()->setting( 'integration/smtp/debug' ) ) $mailer->SMTPDebug = 3;

      // Send via SMTP
      $mailer->isSMTP();

      if( !is_null( fvw()->setting( 'integration/smtp/port' ) ) ) $mailer->Port = fvw()->setting( 'integration/smtp/port' );

      if( !is_null( fvw()->setting( 'integration/smtp/host' ) ) ) $mailer->Host = fvw()->setting( 'integration/smtp/host' );

      if( !is_null( fvw()->setting( 'integration/smtp/username' ) ) ) $mailer->Username = fvw()->setting( 'integration/smtp/username' );

      if( !is_null( fvw()->setting( 'integration/smtp/password' ) ) ) $mailer->Password = fvw()->setting( 'integration/smtp/password' );

      if( !is_null( fvw()->setting( 'integration/smtp/smtp_auth' ) ) ) $mailer->SMTPAuth = fvw()->setting( 'integration/smtp/smtp_auth' );

      if( !is_null( fvw()->setting( 'integration/smtp/smtp_secure' ) ) ) $mailer->SMTPSecure = fvw()->setting( 'integration/smtp/smtp_secure' );

      if( !is_null( fvw()->setting( 'integration/smtp/auto_tls' ) ) ) $mailer->SMTPAutoTLS = fvw()->setting( 'integration/smtp/auto_tls' );

      // Disable SSL check
      $mailer->SMTPOptions = array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );
    } else {

      // Use PHPs default mail function
      $mailer->isMail();
    }

    // Set From
    $mailer->setFrom( fvw()->setting( 'info/email' ), fvw()->setting( 'info/name' ) );

    // Set ReplyTo
    $mailer->addReplyTo( $sender_mail, $sender_name );

    // Set Recipient
    $mailer->addAddress( $recipient_mail );

    // Set as HTML
    $mailer->isHTML( true );

    // Add subject
    $mailer->Subject = $subject;

    // Attachments
    if( $attachments ):
      foreach( $attachments as $key => $file ):
        if( isset( $file[ 'string' ] ) ):
          $mailer->AddStringAttachment( $file['path'], $file['name'] );
        else:
          $mailer->AddAttachment( $file['path'], $file['name'] );
        endif;
      endforeach;
    endif;

    // Fetch email template
    ob_start();

    if( $locate = locate_template( 'email.php' ) ):
      require $locate;
    else:
      require plugin_dir_path( __FILE__ ) . 'email.php';
    endif;

    $email = ob_get_clean();

    // Sends the mail
    $mailer->Body = fvw()->tools()->mapString( $email, array( 'message' => $message ) );

    // Send the mail
    return $mailer->send();
  }
