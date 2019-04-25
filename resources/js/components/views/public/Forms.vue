<template>
    <d-row>
        <d-col cols="12" lg="4" offset-lg="4" md="6" offset-md="3" sm="8" sm-offset="2">
            <d-card class="card-small">
                <d-card-header>
                    <h5 class="mb-0">Fill-up Form</h5>
                </d-card-header>
                <d-card-body>
                    <d-button-group class="w-100 mb-2">
                        <d-button @click="$router.push({
                                name: 'personal-information', 
                                params: {
                                    type: 'employee',
                                }
                            })">Employee</d-button>
                        <d-button @click="$router.push({
                                name: 'personal-information', 
                                params: {
                                    type: 'student',
                                }
                            })">Student</d-button>
                    </d-button-group>
                    <p>Already have an account? <d-link :to="{name: 'index'}">Login here</d-link></p>
                </d-card-body>
            </d-card>
        </d-col>
    </d-row>
</template>

<script>
    export default {
        mounted() {
            if( this.$auth.check() )
                this.$router.push({
                    name : 'portal.index'
                })
        }
    }
</script>

