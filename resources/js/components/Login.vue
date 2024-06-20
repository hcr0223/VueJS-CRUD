<script>
    import { store } from '../store.js'
    export default {
        data() {
            return {     
                passwordRules:[
                    value => !!value || 'Password field is required',
                    value => (value && value.length >= 8) || 'Password must be at least 8 characters'
                ],
                emailRules:[
                    value => !!value || 'Email field is required',
                    value => /^(([^<>()[\]\\.,;:\s@']+(\.[^<>()\\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value) || 'E-mail must be valid'
                ],
                visible: false,
                alertText: null,
                alertTitle: null,
                alertType: 'info',
                user: {
                    email:"",
                    password:""
                },
                store
            }
        },
        methods: {
            loginUser() {
                axios.post('/api/login', this.user)
                .then(response => {
                    this.store.authenticate(response.data.token)
                    this.$router.push('/home')
                })
                .catch(error => {
                    const errors = error.response.data.errors
                    this.visible = true
                    this.alertTitle = 'Error'
                    this.alerType = 'error'
                    this.alertText = `${errors.email[0]} ${errors.password[0]}`
                })
            }
        }
    }
</script>
<template>
    <v-app>
        <v-container>
            <v-sheet class="mx-auto" width="300">
                <v-alert
                    class="multiline"
                    v-show="visible"
                    :title="alertTitle"
                    :text="alertText"
                    :type="alerType"
                ></v-alert>
                <v-form ref="form" v-model="form" @submit.prevent="loginUser">
                    <v-text-field
                        label="Email Address"
                        type="email"
                        v-model="user.email"
                        :rules="emailRules"
                        required
                    ></v-text-field>
                    <v-text-field
                        label="Password"
                        type="password"
                        :rules="passwordRules"
                        v-model="user.password"
                    ></v-text-field>

                    <v-btn
                        color="info"
                        size="large"
                        type="submit"
                        variant="elevated"
                        class="mb-2"
                        block
                    >
                        Login
                    </v-btn>
                    <v-btn
                        color="secondary"
                        size="large"
                        variant="elevated"
                        block
                        @click="this.$router.push('/')"
                    >Register</v-btn>
                </v-form>
            </v-sheet>
        </v-container>
    </v-app>
</template>