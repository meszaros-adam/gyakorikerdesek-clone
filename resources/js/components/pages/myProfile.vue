<template>
    <div class="container my-5 p-5 bg-dark">
        <div>
            <div class="mb-3">
                <label for="nickname" class="form-label">Nickname</label>
                <input v-model="user.nickname" type="text" class="form-control" id="nickname">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input v-model="user.email" type="email" class="form-control" id="email">
            </div>
            <hr>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input v-model="user.password" type="password" class="form-control" id="password">
            </div>
            <button @click="sendUserData" class="btn btn-primary">Submit</button>
        </div>
    </div>
</template>

<script>
import { useToast } from "vue-toastification";
import { ref } from 'vue';
import useCallApi from '../composables/useCallApi';

export default {
    setup() {
        const toast = useToast();

        const user = ref({})

        const getUser = async () => {
            const res = await useCallApi('get', '/get_user_data')
            if (res.status == 200) {
                user.value = res.data
            } else {
                toast.error('Failed to load user data!')
            }
        }

        getUser();

        const sendUserData = async () => {
            const res = await useCallApi('post', '/edit_my_profile', user.value)

            if (res.status == 200){
                toast.success('Profile succesfully updated!')
            }else{
                toast.error('Profile update failed!')
            }
        }

        return { user, sendUserData }
    }
}
</script>