<template>
  <div class="container my-5 p-2 bg-dark text-light">
    <h1>Login</h1>
    <div class="mb-3">
      <label for="email" class="form-label">Email address:</label>
      <input type="email" class="form-control" id="email" />
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password:</label>
      <input type="password" class="form-control" id="password" />
    </div>
    <div class="d-flex justify-content-around">
      <b-button variant="primary">Login</b-button>
      <div class="mb-3 form-check">
        <input
          type="checkbox"
          class="form-check-input"
          id="logged-in-checkbox"
        />
        <label class="form-check-label" for="logged-in-checkbox"
          >Stay logged in!</label
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>