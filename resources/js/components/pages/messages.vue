<template>
    <div class="container my-5 p-5 bg-dark text-light">
        <div class="row g-5">
            <div class="col-md-3">
                <sideMenu></sideMenu>
            </div>
            <div class="col-md-9">
                <div class="container bg-dark text-light">
                    <div v-for="(message, m) in messages" :key="m">
                        {{ message.message }}
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
import { ref } from 'vue'
import useCallApi from '../composables/useCallApi'
import { useToast } from 'vue-toastification'
import sideMenu from "../partials/sideMenu.vue";
export default {
    components: {sideMenu},
    setup() {
        const toast = useToast()
        const messages = ref([])


        const getMessages = async () => {
            const res = await useCallApi('get', '/incoming_messages')

            if (res.status == 200) {
                messages.value = res.data
            }
            else {
                toast.error('Failed to load messages!')
            }
        }

        getMessages()

        return { messages }
    }
}
</script>