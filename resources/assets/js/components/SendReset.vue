<template>
    <form method="get" action="http://over-surgery.test/api/auth/recover">
        <div class="form-group">
            <label for="email">E-mail</label>
            <input name ="email" type="email" id="email" class="form-control" placeholder="user@example.com" required>
        </div>
        <button type="submit" class="btn btn-default">Send Password Reset Link</button>
    </form>
</template>