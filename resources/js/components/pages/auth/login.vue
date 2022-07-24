<template>
  <div class="container my-5 p-5 bg-dark text-light">
    <h1>Login</h1>
    <div class="mb-3">
      <label for="email" class="form-label">Email:</label>
      <input
        v-model="data.email"
        type="email"
        class="form-control"
        id="email"
      />
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password:</label>
      <input
        v-model="data.password"
        type="password"
        class="form-control"
        id="password"
      />
    </div>
    <div class="d-flex justify-content-around">
      <Button @click="login" :loading="loggingIn" type="primary">Login</Button>
      <div class="mb-3 form-check">
        <input
          v-model="data.stayLoggedIn"
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
import { useToast } from "vue-toastification";
export default {
  setup() {
    const toast = useToast();

    return { toast };
  },
  data() {
    return {
      data: {
        email: "",
        password: "",
        stayLoggedIn: false,
      },
      loggingIn: false,
    };
  },
  methods: {
    async login() {
      if (!this.validateEmail(this.data.email))
        return this.toast.warning("You must enter a valid email!");
      if (this.data.password.trim() == "")
        return this.toast.warning("You must enter a password!");

      this.loggingIn = true;

      const res = await this.callApi("post", "/auth/login", this.data);

      this.loggingIn = false;
    },
  },
};
</script>