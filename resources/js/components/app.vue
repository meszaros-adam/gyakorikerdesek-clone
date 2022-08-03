<template>
  <div class="d-flex flex-column bg-black min-vh-100">
    <!-- NAV -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <router-link to="/" class="navbar-brand">FAQ</router-link>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li v-if="!user.getUser" class="nav-item">
              <router-link to="/login" class="nav-link">Login</router-link>
            </li>
            <li v-if="!user.getUser" class="nav-item">
              <router-link to="/register" class="nav-link"
                >Register</router-link
              >
            </li>
            <li v-if="user.getUser" class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                {{ user.getUser.first_name }}
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                  <a class="dropdown-item" href="/auth/logout">Logout</a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled">Disabled</a>
            </li>
          </ul>
          <form class="d-flex" role="search">
            <input
              class="form-control me-2"
              type="search"
              placeholder="Search"
              aria-label="Search"
            />
            <button class="btn btn-dark" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>
    <!-- NAV -->

    <!-- ROUTER -->
    <router-view> </router-view>
    <!-- ROUTER -->

    <!-- FOOTER -->
    <footer class="p-3 bg-primary text-light text-center mt-auto">
      All Rights Reserved!
    </footer>
    <!-- FOOTER -->
  </div>
</template>

<script>
import { ref } from "vue";
import { useUserStore } from "../stores/user";
import { useToast } from "vue-toastification";
import useCallApi from "../components/composables/useCallApi";
export default {
  props: ["user"],
  setup(props) {
    const toast = useToast();

    const user = useUserStore();
    user.setUser(props.user);

    return { user };
  },
};
</script>