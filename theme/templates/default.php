<?php snippet('header'); ?>

<?php dump( $data ); ?>

<script>
    window.siteData = <?= json_encode( $data ); ?>;
</script>

<?php snippet('footer'); ?>
