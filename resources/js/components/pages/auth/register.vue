<template>
  <div class="container my-5 p-5 bg-dark text-light">
    <h1>Register</h1>
    <div class="mb-3">
      <label for="first-name" class="form-label">First name:</label>
      <input
        v-model="data.firstName"
        type="text"
        class="form-control"
        id="first-name"
      />
    </div>
    <div class="mb-3">
      <label for="last-name" class="form-label">Last name:</label>
      <input
        v-model="data.lastName"
        type="text"
        class="form-control"
        id="last-name"
      />
    </div>
    <hr class="bg-primary border-2 border-top border-primary" />
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
      <label for="email-_confirmation" class="form-label"
        >Email _confirmation:</label
      >
      <input
        v-model="data.email_confirmation"
        type="email"
        class="form-control"
        id="email-_confirmation"
      />
    </div>
    <hr class="bg-primary border-2 border-top border-primary" />
    <div class="mb-3">
      <label for="password" class="form-label">Password:</label>
      <input
        v-model="data.password"
        type="password"
        class="form-control"
        id="password"
      />
    </div>
    <div class="mb-3">
      <label for="password-_confirmation" class="form-label"
        >Password _confirmation:</label
      >
      <input
        v-model="data.password_confirmation"
        type="password"
        class="form-control"
        id="password-_confirmation"
      />
    </div>
    <Button @click="register()" :loading="registrating" type="primary"
      >Register</Button
    >
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
        firstName: "",
        lastName: "",
        email: "",
        email_confirmation: "",
        password: "",
        password_confirmation: "",
      },
      registrating: false,
    };
  },
  methods: {
    async register() {
      if (this.data.firstName.trim() == "")
        return this.toast.warning("You need enter first name!");
      if (this.data.lastName.trim() == "")
        return this.toast.warning("You need enter last name!");
      if (this.data.email.trim() == "")
        return this.toast.warning("You need enter email!");
      if (this.data.email_confirmation != this.data.email)
        return this.toast.warning("Emails do not match!");
      if (this.data.password.trim().length < 6)
        return this.toast.warning(
          "Your password must be at least 6 characters!"
        );
      if (this.data.password_confirmation != this.data.password)
        return this.toast.warning("Passwords do not match");

      //validate email with regex
      if (!this.validateEmail(this.data.email))
        return this.toast.warning("You must enter a valide email!");

      this.registrating = true;

      const res = await this.callApi("post", "/auth/register", this.data);

      if(res.status==201){
        this.toast.success('Successfull registration')
        this.$router.push('/login')
      }else{
        this.toast.error(res.data)
      }

      this.registrating = false;
    },
  },
};
</script>