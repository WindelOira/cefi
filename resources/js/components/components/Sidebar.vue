<template>
    <d-col cols="12" lg="2" md="3" tag="aside" class="main-sidebar px-0 shadow-none">
        <div v-if="$auth.user().role == 'admin'" class="nav-wrapper">
            <d-nav class="nav--no-borders" vertical>
                <d-nav-item :to="{name: 'portal.index'}">Dashboard</d-nav-item>
                <d-nav-item :to="{name: 'portal.records', params: {type: 'employee'}}">Employees Records</d-nav-item>
                <d-nav-item :to="{name: 'portal.records', params: {type: 'student'}}">Students Records</d-nav-item>
                <d-nav-item :to="{name: 'portal.computation'}">Computation of Health Complaint</d-nav-item>
            </d-nav>
            <h6 class="main-sidebar__nav-title">Admin</h6>
            <d-nav class="nav--no-borders" vertical>
                <d-dropdown text="Medical" menu-class="dropdown-menu-small" is-nav class="mr-0">
                    <d-dropdown-item :to="{name: 'portal.categories', params: {type: 'medical'}}">Categories</d-dropdown-item>
                </d-dropdown>
            </d-nav>
        </div>
        <div v-else class="nav-wrapper">
            <d-nav class="nav--no-borders" vertical>
                <d-nav-item :to="{name: 'portal.user.dental'}">Dental Records</d-nav-item>
                <d-nav-item :to="{name: 'portal.user.profile.edit'}">Profile</d-nav-item>
            </d-nav>
        </div>
    </d-col>
</template>

