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
                <v-form ref="form" v-model="form" @submit.prevent="registerUser">
                    <v-text-field
                        label="Name"
                        type="text"
                        v-model="register.name"
                        :rules="nameRules"
                        required
                    ></v-text-field>
                    <v-text-field
                        label="Email Address"
                        type="email"
                        v-model="register.email"
                        :rules="emailRules"
                        placeholder="Email Address"
                    ></v-text-field>
                    <v-text-field
                        label="Password"
                        type="password"
                        placeholder="Password"
                        :rules="passwordRules"
                        v-model="register.password"
                    ></v-text-field>

                    <v-btn
                        color="success"
                        size="large"
                        type="submit"
                        variant="elevated"
                        block
                    >
                        Sign Up
                    </v-btn>
                    <br>
                    <v-btn
                        color="info"
                        size="large"
                        type="button"
                        variant="elevated"
                        to="/login"
                        block
                    >
                        Go to Login
                    </v-btn>
                </v-form>
            </v-sheet>
        </v-container>
    </v-app>
</template>
<style>
    .multiline {
        white-space: break-spaces;
    }
</style>
<script>
    export default {
        data(){
            return {
                nameRules:[
                    value => !!value || 'Name field is required'
                ],
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
                register: {
                    name:"",
                    email:"",
                    password:""
                }
            }
        },
        methods: {
            registerUser() {
                axios.post('/api/register', this.register)
                .then(response => {
                    this.visible = true
                    this.alertTitle = "Success"
                    this.alertType = 'success',
                    this.alertText = "User created!!"
                })
                .catch(error => {
                    const errors = error.response.data.errors // Object with validation errors
                    this.visible = true
                    this.alertTitle = 'Error'
                    this.alerType = 'error'
                    this.alertText = `${errors.name[0]}. ${errors.email[0]}. ${errors.password[0]}.`
                })
            }            
        }
    }
</script>