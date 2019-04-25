<template>
    <d-col cols="12" class="main-navbar p-0 py-1">
        <d-container fluid class="px-0">
            <d-navbar type="dark" class="py-0">
                <d-navbar-brand>
                    <d-link :to="{name: $auth.check() ? 'portal.index' : 'index'}">
                        <d-img src="/storage/images/CEFI-logo.png"></d-img>
                    </d-link>
                </d-navbar-brand>

                <d-navbar-nav class="text-center">
                    <d-nav-item v-if="! $auth.check()" :to="{name: 'index'}">Login</d-nav-item>
                    <d-nav-item v-if="$auth.check()" @click.native="$auth.logout()">
                        <vue-material-icon name="power_settings_new" :size="20"></vue-material-icon>
                    </d-nav-item>
                </d-navbar-nav>
            </d-navbar>
        </d-container>
    </d-col>
</template>

