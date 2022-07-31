<template>
  <div class="container my-5 p-5 bg-dark text-light">
    <h1>Register</h1>
    <div class="mb-3">
      <label for="first-name" class="form-label">First name:</label>
      <input
        v-model="registrationData.firstName"
        type="text"
        class="form-control"
        id="first-name"
      />
    </div>
    <div class="mb-3">
      <label for="last-name" class="form-label">Last name:</label>
      <input
        v-model="registrationData.lastName"
        type="text"
        class="form-control"
        id="last-name"
      />
    </div>
    <hr class="bg-primary border-2 border-top border-primary" />
    <div class="mb-3">
      <label for="email" class="form-label">Email:</label>
      <input
        v-model="registrationData.email"
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
        v-model="registrationData.email_confirmation"
        type="email"
        class="form-control"
        id="email-_confirmation"
      />
    </div>
    <hr class="bg-primary border-2 border-top border-primary" />
    <div class="mb-3">
      <label for="password" class="form-label">Password:</label>
      <input
        v-model="registrationData.password"
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
        v-model="registrationData.password_confirmation"
        type="password"
        class="form-control"
        id="password-_confirmation"
      />
    </div>
    <div class="text-end">
      <Button @click="register()" :loading="registrating" type="primary"
        >Register</Button
      >
    </div>
  </div>
</template>

<script>
import { useToast } from "vue-toastification";
import { ref } from "vue";
import useValidateEmail from "../../composables/useValidateEmail";
import useCallApi from "../../composables/useCallApi";
import { useRouter } from "vue-router";
export default {
  setup() {
    const toast = useToast();
    const router = useRouter();

    const registrationData = ref({
      firstName: "",
      lastName: "",
      email: "",
      email_confirmation: "",
      password: "",
      password_confirmation: "",
    });

    const registrating = ref(false);

    async function register() {
      if (registrationData.value.firstName.trim() == "")
        return toast.warning("You need enter first name!");
      if (registrationData.value.lastName.trim() == "")
        return toast.warning("You need enter last name!");
      if (registrationData.value.email.trim() == "")
        return toast.warning("You need enter email!");
      if (
        registrationData.value.email_confirmation !=
        registrationData.value.email
      )
        return toast.warning("Emails do not match!");
      if (registrationData.value.password.trim().length < 6)
        return toast.warning("Your password must be at least 6 characters!");
      if (
        registrationData.value.password_confirmation !=
        registrationData.value.password
      )
        return toast.warning("Passwords do not match");

      //validate email with regex
      if (!useValidateEmail(registrationData.value.email))
        return toast.warning("You must enter a valide email!");

      registrating.value = true;

      const res = await useCallApi(
        "post",
        "/auth/register",
        registrationData.value
      );

      if (res.status == 201) {
        toast.success("Successfull registration");
        router.push("/login");
      } else {
        toast.error(res.data.message);
      }

      registrating.value = false;
    }

    return { toast, registrationData, registrating, register };
  },
};
</script>