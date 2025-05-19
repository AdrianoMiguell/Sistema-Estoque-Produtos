<form action="<?php echo BASE_URL; ?>/users/logout" method="post">
    <legend>Logout</legend>
    <span>Sair de conta atual.</span>
    <input type="hidden" name="request_logout" value="true">
    <button class="btn btn-main">
        <i class="bi bi-trash"></i>
        Logout
    </button>
</form>