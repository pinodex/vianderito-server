<template>
  <div>
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <img src="/assets/img/logo-admin.png">
      </div>

      <div class="navbar-menu">
        <div class="navbar-end">
          <a href="#" class="navbar-item has-dropdown is-hoverable account">
            <div class="navbar-link">{{ $root.account.name }}</div>

            <div class="navbar-dropdown">
              <div class="navbar-item">Logout</div>
            </div>
          </a>
        </div>
      </div>
    </nav>

    <nav class="sidebar">
      
    </nav>
  </div>
</template>

<script>
  export default {

  }
</script>
