<template>
  <div class="d-flex flex-column bg-black min-vh-100">
    <!-- NAV -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <router-link to="/" class="navbar-brand">Frequently Asked Questions</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto">
            <li v-if="!user.getUser" class="nav-item">
              <router-link to="/login" class="nav-link">Login</router-link>
            </li>
            <li v-if="!user.getUser" class="nav-item">
              <router-link to="/register" class="nav-link">Register</router-link>
            </li>
            <li v-if="user.getUser" class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                {{ user.getUser.nickname }}
              </a>
              <ul class="dropdown-menu" style="margin: 0" aria-labelledby="navbarDropdown">
                <li>
                  <a class="dropdown-item" href="/auth/logout">Logout</a>
                </li>
              </ul>
            </li>
          </ul>
          <div class="d-flex" role="search">
            <input v-model="searchString" @keyup.enter="search" class="form-control me-2" type="search"
              placeholder="Search" />
            <button @click="search" class="btn btn-dark">Search</button>
          </div>
        </div>
      </div>
    </nav>
    <!-- NAV -->

    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3">
          <!-- Side Menu -->
          <side-menu></side-menu>
          <!-- Side Menu -->
          <!-- Categories -->
          <div class="bg-secondary mt-5 mx-3 p-3 d-none d-lg-block">
            <h1 class="text-black">Categories</h1>
            <div v-for="(category, c)  in categories" :key="c">
              <div @click="showQuestions.show(`/get_questions_by_category?category_id=${category.id}`,category.name)"
                class="router-link">{{category.name}}</div>
            </div>
          </div>
          <!-- Categories -->
          <!-- Most Popular Tags -->
          <div class="bg-secondary m-5 mx-3 p-3 d-none d-lg-block">
            <h1 class="text-black">Popular Tags</h1>
            <div v-for="(tag, t)  in popTags" :key="t">
              <div @click="showQuestions.show(`/get_questions_by_tag?tag_id=${tag.id}`, '#'+tag.name)"
                class="router-link">
                {{tag.name}}<span>({{tag.questions_count}})</span>
              </div>
            </div>
          </div>
          <!-- Most Popular Tags -->

        </div>
        <div class="col-lg-9">
          <!-- ROUTER -->
          <router-view> </router-view>
          <!-- ROUTER -->
        </div>
      </div>
    </div>

    <!-- FOOTER -->
    <footer class="p-3 bg-primary text-light text-center mt-auto">
      All Rights Reserved!
    </footer>
    <!-- FOOTER -->
  </div>
</template>

<script>
import { ref } from 'vue'
import { useUserStore } from "../stores/user";
import useCallApi from './composables/useCallApi';
import sideMenu from "./partials/sideMenu.vue";
import { useToast } from 'vue-toastification';
import router from '../router';
import useShowQuestions from './composables/useShowQuestions'
export default {
  components: { sideMenu },
  props: ["user"],
  setup(props) {
    const toast = useToast()
    const user = useUserStore();
    user.setUser(props.user);
    const showQuestions = useShowQuestions();

    //categories
    const categories = ref([])

    const getCategories = async () => {
      const res = await useCallApi('get', '/get_all_categories')

      if (res.status == 200) {
        categories.value = res.data
      } else {
        toast.error(res.data.message)
      }
    }
    getCategories();

    //popular tags
    const popTags = ref([])

    const getPopTags = async () => {
      const res = await useCallApi('get', '/get_popular_tags')

      if (res.status == 200) {
        popTags.value = res.data
      } else {
        toast.error(res.data.message)
      }
    }

    getPopTags()

    //search
    const searchString = ref('')

    const search = () => {
      if (searchString.value.trim().length < 2) return toast.warning('The search keyword must be at least 2 characters!')

      showQuestions.show(`/search_question?keyword=${searchString.value}`, searchString.value)

      searchString.value = ""
    }

    return { user, categories, popTags, search, searchString, showQuestions };
  },
};
</script>