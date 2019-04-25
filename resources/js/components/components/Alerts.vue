<template>
    <div>
        <d-alert v-if="alerts.messages" 
                v-for="message in alerts.messages" 
                :key="message" 
                show="1.5" 
                theme="success" 
                @alert-dismissed="remove(message, 'messages')" 
                dismissable>{{ message }}</d-alert>
        <d-alert v-if="alerts.errors" 
                v-for="error in alerts.errors" 
                :key="error" 
                show="1.5" 
                theme="danger" 
                @alert-dismissed="remove(error, 'errors')" 
                dismissable>{{ error }}</d-alert>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                alerts  : {
                    messages    : [],
                    errors      : [],
                }
            }
        },
        methods     : {
            add(message, type) {
                if( type == 'messages' )
                    this.alerts.messages.push(message) 
                else
                    this.alerts.errors.push(message) 
            },
            remove(message, type) {
                if( type == 'messages' )
                    this.alerts.messages.splice(this.alerts.messages.indexOf(message), 1)
                else
                    this.alerts.errors.splice(this.alerts.errors.indexOf(message), 1)
                
            }
        }
    }
</script>
