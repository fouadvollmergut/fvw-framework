  <div class="gdymc_module">
    <div class="modulePadding center">
      <h1>Wartungsarbeiten …</h1>

      <p>Aktuell finden geplante Wartungsarbeiten an unserer Webseite statt, daher steht dieser Bereich aktuell nicht zur Verfügung. Bitte versuchen Sie es später erneut und entschuldigen Sie die Umstände.</p>

      <?php if( !empty( FVW_BLOCK_LIVE_DATA ) ): ?>
        <p class="tb">Für die Wartungsarbeiten ist folgender Zeitrahmen geplant: <?php echo FVW_BLOCK_LIVE_DATA; ?></p>
      <?php endif; ?>

      <?php if( fvw()->setting( 'info/email' ) ): ?>
        <a class="formButtonInline" href="mailto:<?php echo fvw()->setting( 'info/email' ); ?>">Schreiben Sie uns eine E-Mail</a>
      <?php endif; ?>

    </div><!-- .modulePadding -->
  </div><!-- .gdymc_module -->