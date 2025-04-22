<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="flex h-screen">
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="p-4 md:p-6">
            <?php include 'views/index.php'; ?>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>