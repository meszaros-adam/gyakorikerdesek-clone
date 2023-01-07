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
        <button @click="passwordChangeModal = true" class="btn btn-warning my-5">
            Change Password
        </button>

        <!--Password change modal -->
        <b-modal v-model="passwordChangeModal" hide-footer title="Password change">
            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input v-model="newPassword" type="password" id="newPassword" class="form-control">
            </div>
            <div class="mb-3">
                <label for="newPasswordConfirmation" class="form-label">New Password Confirmation</label>
                <input v-model="newPasswordConfirmation" type="password" id="newPasswordConfirmation"
                    class="form-control">
            </div>
            <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password</label>
                <input v-model="currentPassword" type="password" id="currentPassword" class="form-control">
            </div>
            <hr>
            <div class="d-flex justify-content-end mt-3">
                <Button @click="passwordChangeModal = false" class="mx-2">Cancel</Button>
                <Button @click="changePassword" type="primary" :loading="passwordChanging">Change Password</Button>
            </div>
        </b-modal>

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

            if (res.status == 200) {
                toast.success('Profile succesfully updated!')
            } else {
                toast.error('Profile update failed!')
            }
        }

        const passwordChangeModal = ref(false);
        const newPassword = ref('')
        const newPasswordConfirmation = ref('')
        const currentPassword = ref('')
        const passwordChanging = ref(false)

        const changePassword = async () => {
            if (newPassword.value !== newPasswordConfirmation.value) return toast.warning('Password confirmation failed!')
            if (newPassword.value.trim().length < 6) return toast.warning('New Password must be at least 6 characters long!')
            if (currentPassword.value.trim().length < 6) return toast.warning('Current Password must be at least 6 characters long!')

            passwordChanging.value = true
            const res = await useCallApi('post', '/change_password', {
                newPassword: newPassword.value,
                newPassword_confirmation: newPasswordConfirmation.value,
                currentPassword: currentPassword.value
            })

            if (res.status == 200) {
                toast.success('Password changed successfully!')
            } else {
                toast.error('Password change failed!')
            }
            passwordChanging.value = false
            passwordChangeModal.value = false
        }

        return { user, sendUserData, passwordChangeModal, newPassword, newPasswordConfirmation, currentPassword, passwordChanging, changePassword }
    }
}
</script>