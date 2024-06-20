<script>
import { store } from '../store.js'

export default {
    data(){
        return {
            store,
            dialog: false,
            visible: false,
            alertText: '',
            alertTitle: '',
            alertType: 'info',
            headers:[
                {
                    align:'start',
                    key:'name',
                    sortable: false,
                    title: 'Users'
                },
                { key: 'id', title: 'ID'},
                { key: 'name', title: 'Name'},
                { key: 'email', title: 'Email Address'},
                { key: 'edit', title:'Edit'},
                { key: 'delete', title:'Delete'},
            ],
            serverItems: [],
            config: {
                headers:{
                    Authorization: `Bearer ${store.token}`
                }
            },
            editedIndex: -1,
            defaultUser: {
                name:'',
                email: '',
                password: '',
                id: '',
            },
            user:{
                name:'',
                email: '',
                password: '',
                id: '',
            }
        }
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New User' : 'Edit User'
        }
    },
    watch: {
        dialog(val) {
            val || this.close()
        }
    },
    methods: {
        loadUsers() {
            axios.post('/api/get-users', {}, this.config)
            .then(response => {
                this.serverItems = response.data
            })
        },
        editUser(item) {
            this.editedIndex = 1
            this.user = Object.assign({}, item)
            this.dialog = true
        },
        deleteUser(item) {
            this.user = Object.assign({}, item)
            this.$swal({
                title: "Are you sure?",
                text: "You won't able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5c4E",
                confirmButtonText: "Yes, delete it"
            })
            .then((result) => {
                if(result.isConfirmed) {
                    axios.delete(`/api/users/${this.user.id}`, this.config)
                    .then(response => {
                        this.$swal({
                            title:"Deleted!",
                            text:"User has been deleted!",
                            icon:"success"
                        })
                        this.loadUsers()
                    })
                    .catch(error => {
                        this.$swal({
                            title:"Error!",
                            text:"Something went wrong, try later!!",
                            icon: 'error'
                        })
                    })
                }
            })
        },
        logout() {
            this.store.logout()
            this.$router.push('/login')
        },
        open(){
            this.dialog = true
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.user = Object.assign({}, this.defaultUser)
                this.editedIndex = -1
                this.clearAlert()
            }, 300)
        },
        clearAlert() {
            this.visible = false
            this.alertText = ''
            this.alertTitle = ''
            this.alertType = 'info'
        },
        save() {
            if(this.visible) {
                this.clearAlert()
            }
            if(this.editedIndex > -1) {
                console.log(this.user.id)
                axios.put(`/api/users/${this.user.id}`, this.user, this.config)
                .then(response => {
                    this.close()
                    this.$swal({
                        title:"User Edited",
                        text:"User edited successfully!",
                        icon: 'success',
                        timer: 1500
                    })
                    this.loadUsers()
                })
                .catch(error => {
                    const errors = error.response.data.errors // Object with validation errors
                    this.visible = true
                    this.alertTitle = 'Error'
                    this.alerType = 'error'
                    this.alertText += (errors.name) ? `${errors.name[0]}\n` : ''
                    this.alertText += (errors.email) ? `${errors.email[0]}\n` : ''
                    this.alertText += (errors.password) ? `${errors.password[0]}\n` : ''
                })
            } else {
                axios.post(`/api/users`, this.user, this.config)
                .then(response => {
                    this.close()
                    this.$swal({
                        title:"User Created",
                        text:"User created successfully!",
                        icon:"success",
                        timer: 1500
                    })
                    this.loadUsers()
                })
                .catch(error => {
                    const errors = error.response.data.errors // Object with validation errors
                    this.visible = true
                    this.alertTitle = 'Error'
                    this.alerType = 'error'
                    this.alertText += (errors.name) ? `${errors.name[0]}\n` : ''
                    this.alertText += (errors.email) ? `${errors.email[0]}\n` : ''
                    this.alertText += (errors.password) ? `${errors.password[0]}\n` : ''
                })
            }
        }
    }
}
</script>
<template>
    <div>
        <v-toolbar flat color="white">
            <v-toolbar-title>CRUD</v-toolbar-title>
            <v-divider class="mx-2" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="500px">
                <template v-slot:activator="{ on }">
                    <v-btn color="info" size="large" variant="elevated" class="mb-2" @click="open">New User</v-btn>
                    <v-btn color="primary" size="large" variant="elevated" class="mb-2 ml-2" @click="logout">Logout</v-btn>
                </template>
                <v-card>
                    <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container>
                            <v-alert
                                class="multiline"
                                v-show="visible"
                                :title="alertTitle"
                                :text="alertText"
                                :type="alerType"
                            ></v-alert>
                            <v-text-field v-model="user.name" label="Name"></v-text-field>
                            <v-text-field v-model="user.email" label="Email Address"></v-text-field>
                            <v-text-field v-model="user.password" type="password" label="Password"></v-text-field>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="close">Close</v-btn>
                        <v-btn color="blue darken-1" flat @click="save">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-toolbar>
        <v-data-table :headers="headers" :items="serverItems" @update:options="loadUsers">
            <template v-slot:item.edit="{ item }">
                <v-btn elevation="0"  v-on:click="editUser(item)" icon>
                    <v-icon color="blue" icon="$edit"></v-icon>
                </v-btn>
            </template>
            <template v-slot:item.delete="{ item }">
                <v-btn elevation="0" v-on:click="deleteUser(item)" icon>
                    <v-icon color="red" icon="$delete"></v-icon>
                    
                </v-btn>
            </template>
        </v-data-table>
    </div>
</template>