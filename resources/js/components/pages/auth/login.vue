<template>
  <div class="container my-5 p-5 bg-dark text-light">
    <h1>Login</h1>
    <div class="mb-3">
      <label for="email" class="form-label">Email:</label>
      <input
        v-model="loginData.email"
        type="email"
        class="form-control"
        id="email"
      />
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password:</label>
      <input
        v-model="loginData.password"
        type="password"
        class="form-control"
        id="password"
      />
    </div>
    <div class="d-flex justify-content-around">
      <Button @click="login" :loading="loggingIn" type="primary">Login</Button>
      <div class="mb-3 form-check">
        <input
          v-model="loginData.rememberMe"
          type="checkbox"
          class="form-check-input"
          id="logged-in-checkbox"
        />
        <label class="form-check-label" for="logged-in-checkbox"
          >Remember Me!</label
        >
      </div>
    </div>
  </div>
</template>

<script>
import { useToast } from "vue-toastification";
import { useValidateEmail} from "../../../common";
import {  useCallApi } from  "../../../common";
import { ref } from "vue";
export default {
  setup() {
    const toast = useToast();

    const loginData = ref({
      email: "",
      password: "",
      rememberMe: false,
    });

    const loggingIn = ref(false);

    async function login() {
      if (!useValidateEmail(loginData.value.email))
        return toast.warning("You must enter a valid email!");
      if (loginData.value.password.trim() == "")
        return toast.warning("You must enter a password!");

      loggingIn.value = true;

      const res = await useCallApi("post", "/auth/login", loginData.value);

      if (res.status == 200) {
        window.location = "/";
      } else {
        toast.error(res.data);
      }

      loggingIn.value = false;
    }

    return { toast, loginData, loggingIn, login };
  },
};
</script>