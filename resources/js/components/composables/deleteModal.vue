<template>
    <b-modal hide-footer :title=title>
        <div class="mb-3 text-center">
            <i class="bi bi-exclamation-octagon-fill delete-modal-icon"></i>
        </div>
        <h4 class="mb-3">Are you sure you want to delete this {{ item_name }}?</h4>
        <div class="d-flex justify-content-end">
            <Button @click="closeModal" class="mx-2">Cancel</Button>
            <Button @click="deleteItem" type="primary" :loading="deleting">Delete</Button>
        </div>
    </b-modal>
</template>

<script>
import { computed } from 'vue'
import useCallApi from './useCallApi'
import { useToast } from 'vue-toastification'
import { ref } from 'vue'

export default {
    props: ['delete_url', 'item_name', 'item_id', 'delete_index'],
    setup(props, context) {
        const title = computed(() => "Delete " + props.item_name)
        const toast = useToast()

        //close event with v-model
        const closeModal = () => {
            context.emit("update:modelValue", false)
        }

        //delete
        const deleting = ref(false)
        const deleteItem = async () => {
            deleting.value = true
            const res = await useCallApi('post', props.delete_url, { id: props.item_id })

            if (res.status == 200) {
                toast.success('Item was deleted successfuly!')
                context.emit('successfullDelete', props.delete_index)
            } else {
                toast.error('Delete failed!')
            }

            closeModal();
            deleting.value = false
        }

        return { title, closeModal, deleting, deleteItem }
    }
}
</script>