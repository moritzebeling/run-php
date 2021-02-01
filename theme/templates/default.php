<?php snippet('header'); ?>

Welcome to this website

<?php dump( run()->debug() ) ?>

<script>
    window.siteData = <?= json_encode( $data ); ?>;
</script>

<?php snippet('footer'); ?>
