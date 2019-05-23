<template>
    <d-row>
        <d-col cols="12" lg="4" offset-lg="4" md="6" offset-md="3" sm="8" offset-sm="2">
            <d-card class="card-small">
                <d-card-header>
                    <h5 class="mb-0">Login</h5>
                </d-card-header>
                <d-card-body>
                    <vue-form :state="formState" @submit.prevent="login">
                        <validate class="form-group">
                            <label class="mb-1">Email Address</label>
                            <d-input v-model="model.email" 
                                    name="email" 
                                    type="email" 
                                    class="form-control-sm" 
                                    required></d-input>
                        </validate>
                        <validate class="form-group">
                            <label class="mb-1">Password</label>
                            <d-input v-model="model.password" 
                                    name="password" 
                                    type="password" 
                                    class="form-control-sm" 
                                    required></d-input>
                        </validate>

                        <div class="d-flex">
                            <d-button>Login</d-button>
                            <d-button theme="light" 
                                    @click="$router.push({name: 'index'})" 
                                    class="ml-auto d-inline-flex align-items-center">
                                <vue-material-icon name="keyboard_backspace" size="20"></vue-material-icon>
                                <span class="ml-2">Return</span>
                            </d-button>
                        </div>
                    </vue-form>
                </d-card-body>
            </d-card>    
        </d-col> 
    </d-row>
</template>

<script>
    import Forms from '@js/mixins/forms.js'

    export default {
        mixins  : [
            Forms,
        ],
        data() {
            return {
                formState   : {},
                model       : {
                    email       : '',
                    password    : '',
                }
            }
        },
        methods : {
            login() {
                var app = this

                this.$auth.login({
                    params  : {
                        email       : app.model.email,
                        password    : app.model.password,
                    },
                    success : function() {
                        this.$router.push({
                            name : 'portal.index',
                        })
                    },
                    error   : function() {
                        console.log(0)
                    },
                    fetchUser : true,
                })
            }
        },
        mounted() {
            if( this.$auth.check() )
                this.$router.push({
                    name : 'portal.index'
                })
        }
    }
</script>
