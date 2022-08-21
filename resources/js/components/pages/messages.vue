<template>
    <div class="container my-5 p-5 bg-dark text-light">
        <div v-for="(message, m) in messages" :key="m">
            {{ message.message }}
        </div>
    </div>
</template>

<script>
import { ref } from 'vue'
import useCallApi from '../composables/useCallApi'
import { useToast } from 'vue-toastification'
export default {
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